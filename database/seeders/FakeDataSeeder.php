<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Alias untuk DatabaseSeeder (kompatibilitas command lama).
 */
class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        (new DatabaseSeeder)->run();
    }
}
