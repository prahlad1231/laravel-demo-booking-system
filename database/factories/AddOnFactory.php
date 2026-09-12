<?php

namespace Database\Factories;

use App\Models\AddOn;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AddOn>
 */
class AddOnFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => \fake()->randomElement(['Parking', 'Guided tour', 'Lunch', 'Audio guide']),
            'price_cents' => \fake()->numberBetween(100, 2000),
        ];
    }
}
