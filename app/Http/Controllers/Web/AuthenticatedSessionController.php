<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller {
    public function create(): Response {
        return Inertia::render('Auth/Login');
    }

    public function store(LoginRequest $loginRequest): RedirectResponse {
        if (! Auth::attempt($loginRequest->validated())) {
            throw ValidationException::withMessages([
                'email' => 'The credentials do not match our records.',
            ]);
        }

        $loginRequest->session()->regenerate();

        return \redirect()->intended(\route('spaces.index'));
    }

    public function destroy(Request $request): RedirectResponse {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return \redirect()->route('home');
    }
}
