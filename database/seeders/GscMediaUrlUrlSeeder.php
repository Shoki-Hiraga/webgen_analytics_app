<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GscMediaUrlUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URLリストを外部ファイルから読み込み
        $filePath = base_path('dataget_app/setting_file/Search_Console_set/GSC_QshURL_Historia.php');
        $urls = require $filePath;

        foreach ($urls as $url) {
            DB::table('gsc_media_url_listurls')->updateOrInsert(
                ['url' => $url],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
