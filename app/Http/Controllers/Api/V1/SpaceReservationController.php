<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Space;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpaceReservationController extends Controller {
    public function index(Space $space): AnonymousResourceCollection {
        return ReservationResource::collection(
            $space->reservations()->cursorPaginate()
        );
    }
}
