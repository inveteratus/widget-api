<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Widget>
 */
class WidgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => str(fake()->unique()->slug(fake()->numberBetween(2, 3)))->headline(),
            'cost' => fake()->numberBetween(0, 100) * 100 + 99,
            'in_stock' => fake()->boolean(80),
        ];
    }
}
