<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Response;

class ReservationController extends Controller {
    public function update(UpdateReservationRequest $reservationRequest, Reservation $reservation): ReservationResource {
        $reservation->update($reservationRequest->validated());

        return new ReservationResource($reservation);
    }

    public function destroy(Reservation $reservation): Response {
        $reservation->delete();

        return \response()->noContent();
    }
}
