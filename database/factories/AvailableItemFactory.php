<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvailableItem>
 */
class AvailableItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(["procedure", "material", "part"]),
            'nice_name' => ucfirst(fake()->word()),
            'price' => fake()->numberBetween(1000, 20000)
        ];
    }
}
