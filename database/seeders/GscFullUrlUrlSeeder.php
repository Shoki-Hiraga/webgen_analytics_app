<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GscFullUrlUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URLリストを外部ファイルから読み込み
        $filePath = base_path('dataget_app/setting_file/Search_Console_set/GSC_QshURL_Maker.php');
        $urls = require $filePath;

        foreach ($urls as $url) {
            DB::table('gsc_fullurl_listurls')->updateOrInsert(
                ['url' => $url],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
