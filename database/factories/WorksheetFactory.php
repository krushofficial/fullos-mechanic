<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worksheet>
 */
class WorksheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'advisor_id' => 1,
            'plate' => "ABC-123",
            'year' => fake()->numberBetween(1990, 2024),
            'type' => "Kia Sorento",
            'owner_name' => fake()->name(),
            'owner_address' => fake()->address()
        ];
    }
}
