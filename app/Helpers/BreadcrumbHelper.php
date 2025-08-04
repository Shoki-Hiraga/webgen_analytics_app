<?php

namespace App\Helpers;

use App\Models\SetSlug;

class BreadcrumbHelper
{
    /**
     * パンくずリストを現在の URL に基づいて生成
     */
    public static function generate(): array
    {
        $currentSlug = request()->path(); // 例: "ga4_qsha_oh/maker"
        $page = SetSlug::where('slug', $currentSlug)->where('active', true)->first();

        $breadcrumbs = [];

        while ($page) {
            $breadcrumbs[] = [
                'name' => $page->label,
                'url'  => url($page->slug),
            ];
            $page = $page->parent;
        }

        return array_reverse($breadcrumbs);
    }

    /**
     * ナビゲーションやフッター用のリンク一覧（グループ化）
     */
    public static function getLinks(): array
    {
        return SetSlug::where('active', true)
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type') // 例: 'ga4', 'gsc'
            ->map(function ($items) {
                return $items->mapWithKeys(function ($item) {
                    return [
                        $item->slug => [
                            'name' => $item->label,
                            'url'  => url($item->slug),
                        ],
                    ];
                });
            })->toArray();
    }

    /**
     * フラットなリンク一覧（ルート名ベースではなく slug ベース）
     */
    public static function getFlatLinks(): array
    {
        return SetSlug::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->slug => [
                        'name' => $item->label,
                        'url'  => url($item->slug),
                    ],
                ];
            })->toArray();
    }
}
