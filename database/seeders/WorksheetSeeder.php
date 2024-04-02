<?php

namespace Database\Seeders;

use App\Models\Worksheet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorksheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Worksheet::factory(10)->create();
    }
}
