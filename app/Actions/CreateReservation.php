<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateReservation {
    public function handle(Space $space, User $user, array $attributes): Reservation {
        $attributes = \array_merge($attributes, ['user_id' => $user->id]);

        return DB::transaction(function () use ($space, $attributes) {
            $startsAt = CarbonImmutable::parse($attributes['starts_at']);
            $endsAt = CarbonImmutable::parse($attributes['ends_at']);

            $booked = $space->reservations()
                ->active()
                ->overlapping($startsAt, $endsAt)
                ->lockForUpdate()
                ->sum('capacity_used');

            $remaining = $space->capacity - $booked;

            if ((int) $attributes['capacity_used'] > $remaining) {
                throw ValidationException::withMessages([
                    'capacity_used' => "Only {$remaining} place(s) remain for this time.",
                ]);
            }

            return $space->reservations()->create($attributes);
        });
    }
}
