<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // If user is not OTP-verified (email_verified_at null), force OTP verification first
        $user = Auth::user();
        if (!$user->email_verified_at) {
            // keep the email for OTP page
            session(['email' => $user->email]);
            // log out to prevent access before verification
            Auth::logout();
            return redirect()->route('otp.verify')->with('email', $user->email);
        }

        // Check if there's an intended URL (like cart page)
        $intendedUrl = session('intended_url');
        if ($intendedUrl) {
            session()->forget('intended_url');
            return redirect($intendedUrl);
        }

        // Redirect based on user role
        if ($user->hasRole('chef')) {
            return redirect()->route('chef.dashboard');
        } elseif ($user->hasRole('delivery')) {
            return redirect()->route('delivery.dashboard'); // We'll create this later
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
