<?php

namespace Database\Seeders;

use App\Models\AvailableItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailableItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AvailableItem::factory(10)->create();
    }
}
