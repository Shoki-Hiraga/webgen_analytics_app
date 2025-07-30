<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Ga4DirectoryUrlSeeder::class,
            Ga4FullUrlUrlSeeder::class,
            Ga4MediaUrlUrlSeeder::class,
            GscqueriesSeeder::class,
            GscMediaUrlUrlSeeder::class,
            GscFullUrlUrlSeeder::class,
        ]);
    }
}
