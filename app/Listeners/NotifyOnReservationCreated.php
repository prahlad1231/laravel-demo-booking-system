<?php

namespace App\Listeners;

use App\Enums\UserRole;
use App\Events\ReservationCreated;
use App\Models\User;
use App\Notifications\ReservationCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Support\Facades\Notification;

class NotifyOnReservationCreated implements ShouldQueueAfterCommit {
    /**
     * Handle the event.
     */
    public function handle(ReservationCreated $event): void {
        $reservation = $event->reservation;
        $reservation->loadMissing('space');

        $staff = User::query()
            ->where('organisation_id', $reservation->space->organisation_id)
            ->whereIn('role', [UserRole::Owner, UserRole::Manager])
            ->get();

        Notification::send($staff, new ReservationCreatedNotification($reservation));
    }
}
