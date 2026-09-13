<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory {
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => \fake()->name(),
            'email' => \fake()->unique()->safeEmail(),
            'email_verified_at' => \now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::Customer,
            'organisation_id' => null,
        ];
    }

    public function admin(): static {
        return $this->state(['role' => UserRole::Admin]);
    }

    public function owner(): static {
        return $this->state([
            'role' => UserRole::Owner,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function manager(): static {
        return $this->state([
            'role' => UserRole::Manager,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function receptionist(): static {
        return $this->state([
            'role' => UserRole::Receptionist,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function customer(): static {
        return $this->state(['role' => UserRole::Customer]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
