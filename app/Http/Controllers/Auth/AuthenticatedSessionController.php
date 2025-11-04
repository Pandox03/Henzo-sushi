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
        // This should only happen on the first login after registration
        // NOTE: Admin, Chef, and Delivery roles do NOT require OTP verification
        $user = Auth::user();
        
        // Refresh user to ensure we have the latest data from database
        $user->refresh();
        
        // Skip OTP verification for admin, chef, and delivery roles
        $skipOtpRoles = ['admin', 'chef', 'delivery'];
        $isNonCustomer = $user->hasAnyRole($skipOtpRoles);
        
        // Auto-verify non-customer roles on first login (if not already verified)
        if ($isNonCustomer && !$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
            \Log::info('Non-customer role auto-verified (skipping OTP)', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray()
            ]);
        }
        
        // Only customers require OTP verification
        if (!$isNonCustomer && !$user->email_verified_at) {
            // keep the email for OTP page
            session(['email' => $user->email]);
            // log out to prevent access before verification
            Auth::logout();
            \Log::info('Customer requires OTP verification on login', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return redirect()->route('otp.verify')->with('email', $user->email);
        }
        
        // User is already verified, proceed with normal login flow
        \Log::info('User logged in successfully (already verified)', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ]);

        // Check if there's an intended URL (like cart page)
        $intendedUrl = session('intended_url');
        if ($intendedUrl) {
            session()->forget('intended_url');
            return redirect($intendedUrl);
        }

        // Redirect based on user role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('chef')) {
            return redirect()->route('chef.dashboard');
        } elseif ($user->hasRole('delivery')) {
            return redirect()->route('delivery.dashboard');
        }

        // Customer users should be redirected to home page, not dashboard
        return redirect()->route('home');
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
