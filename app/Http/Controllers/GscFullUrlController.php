<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GscFullUrl;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GscFullUrlController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = GscFullUrl::query();

        // 期間指定
        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->start_month)->startOfMonth();
            $baseQuery->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->end_month)->endOfMonth();
            $baseQuery->where('start_date', '<=', $end);
        }

        // ==========================
        // 計算モード
        // ==========================
        if ($request->filled('calculate')) {

            $calculatedQuery = (clone $baseQuery)
                ->selectRaw('
                    page_url,
                    SUM(total_impressions) as total_impressions,
                    SUM(total_clicks) as total_clicks,
                    AVG(avg_position) as avg_position
                ')
                ->groupBy('page_url')
                ->orderByDesc('total_impressions');

            // ==========================
            // CSV出力
            // ==========================
            if ($request->filled('csv')) {
                return $this->exportCsv($calculatedQuery);
            }

            // ページ表示用
            $records = $calculatedQuery
                ->paginate(100)
                ->withQueryString();

            // CTR再計算
            $records->getCollection()->transform(function ($row) {
                $row->avg_ctr = $row->total_impressions > 0
                    ? round(($row->total_clicks / $row->total_impressions) * 100, 2)
                    : 0;
                return $row;
            });

            // ===== サマリー =====
            $summary = (clone $baseQuery)
                ->selectRaw('
                    SUM(total_impressions) as total_impressions,
                    SUM(total_clicks) as total_clicks,
                    AVG(avg_position) as avg_position
                ')
                ->first();

            $summary->avg_ctr = $summary->total_impressions > 0
                ? round(($summary->total_clicks / $summary->total_impressions) * 100, 2)
                : 0;

            return view(
                'main.gsc_fullurl_period',
                compact('records', 'summary')
            );
        }

        // ==========================
        // 通常（未計算）
        // ==========================
        $records = $baseQuery
            ->orderBy('start_date', 'desc')
            ->paginate(100)
            ->withQueryString();

        return view('main.gsc_fullurl_period', compact('records'));
    }

    /**
     * CSV出力（計算後データ）
     */
    private function exportCsv($query): StreamedResponse
    {
        $filename = 'gsc_fullurl_calculated_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // ヘッダー
            fputcsv($handle, [
                'Page URL',
                'Impressions',
                'Clicks',
                'CTR (%)',
                'Average Position',
            ]);

            $query->chunk(1000, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    $ctr = $row->total_impressions > 0
                        ? round(($row->total_clicks / $row->total_impressions) * 100, 2)
                        : 0;

                    fputcsv($handle, [
                        $row->page_url,
                        $row->total_impressions,
                        $row->total_clicks,
                        $ctr,
                        round($row->avg_position, 2),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
