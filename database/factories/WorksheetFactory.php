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
            'plate' => strtoupper(fake()->bothify("???-###")),
            'make' => fake()->randomElement(["Kia", "Opel", "BMW"]),
            'type' => fake()->randomElement(["Stinger", "Astra", "3 Series"]),
            'owner_name' => fake()->name(),
            'owner_address' => fake()->address()
        ];
    }
}
