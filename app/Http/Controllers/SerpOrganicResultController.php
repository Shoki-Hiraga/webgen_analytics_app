<?php

namespace App\Http\Controllers;

use App\Models\SerpOrganicResult;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SerpOrganicResultController extends Controller
{
    public function index()
    {
        $query = SerpOrganicResult::query();

        // 🔍 original_keyword フィルタ（部分一致）
        if (request()->filled('original_keyword')) {
            $keyword = request()->original_keyword;
            $query->where('original_keyword', 'like', '%' . $keyword . '%');
        }

        $results = $query->orderBy('id', 'asc')
            ->paginate(100)
            ->appends(request()->query()); // ← ページ送り時も検索条件維持

        return view('main.serp_organic_results_index', compact('results'));
    }

    public function csv()
    {
        $query = SerpOrganicResult::query();

        // 🔍 検索条件を CSV にも反映
        if (request()->filled('original_keyword')) {
            $keyword = request()->original_keyword;
            $query->where('original_keyword', 'like', '%' . $keyword . '%');
        }

        $results = $query->orderBy('id', 'asc')->get();

        $filename = 'serp_organic_results_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'id',
            'original_keyword',
            'product',
            'priority',
            'fetched_date',
            'rank',
            'keyword',
            'url',
            'title',
            'created_at',
        ];

        return new StreamedResponse(function () use ($results, $columns) {
            $output = fopen('php://output', 'w');
            
            // 日本語向け BOM 付き UTF-8
            fwrite($output, "\xEF\xBB\xBF");

            // ヘッダ行
            fputcsv($output, $columns);

            // データ行
            foreach ($results as $row) {
                fputcsv($output, [
                    $row->id,
                    $row->original_keyword,
                    $row->product,
                    $row->priority,
                    $row->fetched_date,
                    $row->rank,
                    $row->keyword,
                    $row->url,
                    $row->title,
                    $row->created_at,
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }

}
