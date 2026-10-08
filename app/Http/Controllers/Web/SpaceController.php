<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpaceResource;
use App\Models\Space;
use Inertia\Inertia;
use Inertia\Response;

class SpaceController extends Controller {
    public function index(): Response {
        return Inertia::render('Spaces/Index', [
            'spaces' => SpaceResource::collection(Space::query()->get())->resolve(),
        ]);
    }
}
