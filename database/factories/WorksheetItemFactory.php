<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorksheetItem>
 */
class WorksheetItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'worksheet_id' => fake()->numberBetween(1, 10),
            'item_id' => fake()->numberBetween(1, 10),
            'quantity' => fake()->numberBetween(1, 30)
        ];
    }
}
