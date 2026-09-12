<?php

namespace Database\Factories;

use App\Enums\OrganisationType;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organisation>
 */
class OrganisationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => \fake()->company(),
            'type' => OrganisationType::Attraction,
        ];
    }

    public function attraction(): static {
        return $this->state(['type' => OrganisationType::Attraction]);
    }

    public function school(): static {
        return $this->state(['type' => OrganisationType::School]);
    }

    public function tourOperator(): static {
        return $this->state(['type' => OrganisationType::TourOperator]);
    }
}
