<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationController extends Controller {
    public function index(Request $request): AnonymousResourceCollection {
        return NotificationResource::collection(
            $request->user()->unreadNotifications()->cursorPaginate()
        );
    }
}
