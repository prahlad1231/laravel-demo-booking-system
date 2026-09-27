<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\CreateReservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexReservationsRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Space;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SpaceReservationController extends Controller {
    public function index(IndexReservationsRequest $reservationsRequest, Space $space): AnonymousResourceCollection {
        return ReservationResource::collection(
            $space->reservations()
                ->when($reservationsRequest->date('from'), fn (Builder $q, CarbonImmutable $from) => $q->where('starts_at', '>=', $from))
                ->when($reservationsRequest->date('to'), fn (Builder $q, CarbonImmutable $to) => $q->where('starts_at', '<', $to->addDay()))
                ->cursorPaginate()
        );
    }

    public function store(StoreReservationRequest $reservationRequest, Space $space, CreateReservation $createReservationAction): JsonResponse {
        $reservation = $createReservationAction->handle($space, $reservationRequest->user(), $reservationRequest->validated());

        return (new ReservationResource($reservation))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
