<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexReservationsRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Space;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpaceReservationController extends Controller {
    public function index(IndexReservationsRequest $request, Space $space): AnonymousResourceCollection {
        return ReservationResource::collection(
            $space->reservations()->when($request->date('from'), fn (Builder $q, CarbonImmutable $from) => $q->where('starts_at', '>=', $from))
                ->when($request->date('to'), fn (Builder $q, CarbonImmutable $to) => $q->where('starts_at', '<', $to->addDay()))
                ->cursorPaginate()
        );
    }
}
