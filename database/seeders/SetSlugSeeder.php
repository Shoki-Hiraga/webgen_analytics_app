<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetSlugSeeder extends Seeder
{
    public function run(): void
    {
        // ID管理のために一時保存
        $ids = [];

        // 親：GA4 一覧
        $ids['ga4_qsha_oh'] = DB::table('set_slug')->insertGetId([
            'slug' => 'ga4_qsha_oh',
            'label' => 'GA4 一覧',
            'type' => 'ga4',
            'handler' => 'index',
            'parent_id' => null,
            'active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 子：GA4配下
        $ga4Children = [
            ['slug' => 'ga4_qsha_oh/maker',     'label' => 'GA4 /maker/',     'handler' => 'show'],
            ['slug' => 'ga4_qsha_oh/result',    'label' => 'GA4 /result/',    'handler' => 'show'],
            ['slug' => 'ga4_qsha_oh/usersvoice','label' => 'GA4 /usersvoice/','handler' => 'show'],
            ['slug' => 'ga4_qsha_oh/historia',  'label' => 'GA4 /historia/',  'handler' => 'show'],
            ['slug' => 'ga4_qsha_oh/yoy',       'label' => 'GA4 YoY比較',     'handler' => 'yoy'],
            ['slug' => 'ga4_qsha_oh/mom',       'label' => 'GA4 MoM比較',     'handler' => 'mom'],
        ];

        foreach ($ga4Children as $index => $child) {
            DB::table('set_slug')->insert([
                'slug' => $child['slug'],
                'label' => $child['label'],
                'type' => 'ga4',
                'handler' => $child['handler'],
                'parent_id' => $ids['ga4_qsha_oh'],
                'active' => true,
                'sort_order' => $index + 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 親：GSC 一覧
        $ids['gsc_qsha_oh'] = DB::table('set_slug')->insertGetId([
            'slug' => 'gsc_qsha_oh',
            'label' => 'GSC 一覧',
            'type' => 'gsc',
            'handler' => 'index',
            'parent_id' => null,
            'active' => true,
            'sort_order' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 子：GSC配下
        $gscChildren = [
            ['slug' => 'gsc_qsha_oh/maker',     'label' => 'GSC /maker/',     'handler' => 'show'],
            ['slug' => 'gsc_qsha_oh/result',    'label' => 'GSC /result/',    'handler' => 'show'],
            ['slug' => 'gsc_qsha_oh/usersvoice','label' => 'GSC /usersvoice/','handler' => 'show'],
            ['slug' => 'gsc_qsha_oh/historia',  'label' => 'GSC /historia/',  'handler' => 'show'],
            ['slug' => 'gsc_qsha_oh/yoy',       'label' => 'GSC YoY比較',     'handler' => 'yoy'],
            ['slug' => 'gsc_qsha_oh/mom',       'label' => 'GSC MoM比較',     'handler' => 'mom'],
        ];

        foreach ($gscChildren as $index => $child) {
            DB::table('set_slug')->insert([
                'slug' => $child['slug'],
                'label' => $child['label'],
                'type' => 'gsc',
                'handler' => $child['handler'],
                'parent_id' => $ids['gsc_qsha_oh'],
                'active' => true,
                'sort_order' => $index + 11,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
