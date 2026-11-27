<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * One login for both roles; the role only decides where the user lands.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return redirect()->route($request->user()->homeRoute())
            ->with('status', 'Signed in successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $redirectTo = $request->user()->isAdmin() ? 'admin.login' : 'landing';

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectTo)->with('status', 'You have been logged out.');
    }
}
