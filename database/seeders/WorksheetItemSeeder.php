<?php

namespace Database\Seeders;

use App\Models\WorksheetItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorksheetItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorksheetItem::factory(30)->create();
    }
}
