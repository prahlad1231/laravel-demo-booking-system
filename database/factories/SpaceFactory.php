<?php

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Space>
 */
class SpaceFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => \fake()->streetName(),
            'capacity' => \fake()->numberBetween(10, 200),
            'organisation_id' => Organisation::factory(),
        ];
    }
}
