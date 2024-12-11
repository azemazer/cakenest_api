<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promocode>
 */
class PromocodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'validity_date' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'code' => fake()->word(),
            'percentage' => fake()->numberBetween(0, 100)
        ];
    }
}
