<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpaceResource;
use App\Models\Space;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpaceController extends Controller {
    public function index(): AnonymousResourceCollection {
        return SpaceResource::collection(Space::query()->cursorPaginate());
    }
}
