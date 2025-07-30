<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ga4MediaUrlUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URLリストを外部ファイルから読み込み
        $filePath = base_path('dataget_app/setting_file/GA4_Set/GA4_QshURL_Historia.php');
        $urls = require $filePath;

        foreach ($urls as $url) {
            DB::table('ga4_media_url_url')->updateOrInsert(
                ['url' => $url],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
