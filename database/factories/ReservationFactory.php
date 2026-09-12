<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $startsAt = Carbon::parse(\fake()->dateTimeBetween('+1 day', '+30 days'));
        return [
            'space_id' => Space::factory(),
            'user_id' => User::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHours(2),
            'capacity_used' => \fake()->numberBetween(5, 20),
            'status' => ReservationStatus::Pending,
        ];
    }

    public function pending(): static {
        return $this->state(['status' => ReservationStatus::Pending]);
    }

    public function confirmed(): static {
        return $this->state(['status' => ReservationStatus::Confirmed]);
    }

    public function declined(): static {
        return $this->state(['status' => ReservationStatus::Declined]);
    }

    public function cancelled(): static {
        return $this->state(['status' => ReservationStatus::Cancelled]);
    }
}
