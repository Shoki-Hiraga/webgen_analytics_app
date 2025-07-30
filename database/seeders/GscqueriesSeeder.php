<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GscqueriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // クエリリストを外部ファイルから読み込み
        $filePath = base_path('dataget_app/setting_file/Search_Console_set/GSC_QshQuery.php');
        $queries = require $filePath;

        foreach ($queries as $query) {
            DB::table('gsc_query_listqueries')->updateOrInsert(
                ['query' => $query],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
