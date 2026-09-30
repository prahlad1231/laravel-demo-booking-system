<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Response;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class ReservationController extends Controller {
    #[Authorize('update', 'reservation')]
    public function update(UpdateReservationRequest $reservationRequest, Reservation $reservation): ReservationResource {
        $reservation->update($reservationRequest->validated());

        return new ReservationResource($reservation);
    }

    #[Authorize('delete', 'reservation')]
    public function destroy(Reservation $reservation): Response {
        $reservation->delete();

        return \response()->noContent();
    }
}
