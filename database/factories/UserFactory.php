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
            'role' => UserRole::Individual,
            'organisation_id' => null,
        ];
    }

    public function admin(): static {
        return $this->state(['role' => UserRole::Admin]);
    }

    public function attractionOwner(): static {
        return $this->state([
            'role' => UserRole::AttractionOwner,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function attractionAdmin(): static {
        return $this->state([
            'role' => UserRole::AttractionAdmin,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function attractionReceptionist(): static {
        return $this->state([
            'role' => UserRole::AttractionReceptionist,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function schoolUser(): static {
        return $this->state([
            'role' => UserRole::SchoolUser,
            'organisation_id' => Organisation::factory(),
        ]);
    }

    public function individual(): static {
        return $this->state(['role' => UserRole::Individual]);
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
