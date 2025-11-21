<?php

namespace App\Http\Controllers;

use App\Models\AdsKeywordPlannerResult;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdsKeywordPlannerResultController extends Controller
{
    /**
     * 一覧表示
     */
    public function index()
    {
        $query = AdsKeywordPlannerResult::query();

        // 🔍 original_keyword 検索
        if (request()->filled('original_keyword')) {
            $key = request()->original_keyword;
            $query->where('original_keyword', 'like', '%' . $key . '%');
        }

        $results = $query->orderBy('id', 'asc')
            ->paginate(100)
            ->appends(request()->query());

        return view('main.ads_keyword_planner_results_index', compact('results'));
    }

    /**
     * CSV ダウンロード
     */
    public function csv()
    {
        $query = AdsKeywordPlannerResult::query();

        if (request()->filled('original_keyword')) {
            $key = request()->original_keyword;
            $query->where('original_keyword', 'like', '%' . $key . '%');
        }

        $results = $query->orderBy('id', 'asc')->get();

        $filename = 'ads_keyword_planner_results_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'id',
            'original_keyword',
            'product',
            'priority',
            'keyword',
            'avg_monthly_search_volume',
            'competition_level',
            'competition_index',
            'low_cpc',
            'high_cpc',
            'created_at',
        ];

        return new StreamedResponse(function () use ($results, $columns) {
            $output = fopen('php://output', 'w');

            // UTF-8 (Excel 文字化け防止)
            fwrite($output, "\xEF\xBB\xBF");

            // ヘッダー行
            fputcsv($output, $columns);

            // データ行
            foreach ($results as $row) {
                fputcsv($output, [
                    $row->id,
                    $row->original_keyword,
                    $row->product,
                    $row->priority,
                    $row->keyword,
                    $row->avg_monthly_search_volume,
                    $row->competition_level,
                    $row->competition_index,
                    $row->low_cpc,
                    $row->high_cpc,
                    $row->created_at,
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }
}
