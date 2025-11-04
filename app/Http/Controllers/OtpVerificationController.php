<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FirstOrderPromoMail;
use Illuminate\Support\Str;

class OtpVerificationController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        // If user is already authenticated and verified, redirect them away
        if (Auth::check()) {
            $user = Auth::user();
            $user->refresh(); // Get latest state from database
            
            if ($user->email_verified_at) {
                \Log::info('Authenticated user already verified, redirecting from OTP page', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                return redirect()->route('home');
            }
        }
        
        $email = $request->get('email') ?: session('email');
        
        \Log::info('OTP verification page accessed', [
            'email_from_request' => $request->get('email'),
            'email_from_session' => session('email'),
            'final_email' => $email,
            'auth_check' => Auth::check(),
            'auth_user_id' => Auth::id()
        ]);
        
        // If user is already verified, redirect them away from OTP page
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Non-customer roles (admin, chef, delivery) should not access OTP page
                $skipOtpRoles = ['admin', 'chef', 'delivery'];
                if ($user->hasAnyRole($skipOtpRoles)) {
                    \Log::warning('Non-customer role attempted to access OTP page', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'roles' => $user->roles->pluck('name')->toArray()
                    ]);
                    return redirect()->route('home');
                }
                
                $user->refresh(); // Get latest verification status
                if ($user->email_verified_at) {
                    // User already verified, redirect to home
                    return redirect()->route('home');
                }
            }
        }
        
        // Auto-send OTP if email is provided and user is not verified
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user && !$user->email_verified_at) {
                // Generate 6-digit OTP
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                
                // Set OTP expiry to 10 minutes from now
                $user->update([
                    'otp_code' => $otp,
                    'otp_expires_at' => now()->addMinutes(10)
                ]);

                // Send OTP via email
                try {
                    Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                        $message->to($user->email, $user->name)
                                ->subject('Verify Your Email - Henzo Sushi');
                    });
                    
                    \Log::info('OTP sent successfully', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'otp' => $otp
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the page load
                    \Log::error('Failed to send OTP: ' . $e->getMessage());
                    
                    // For development, log the OTP so we can see it
                    \Log::info('OTP for development (email failed): ' . $otp);
                }
            }
        }
        
        return view('auth.verify-email', compact('email'));
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        
        // Check if user is already verified - no need to send OTP
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified. You can log in directly.'
            ], 400);
        }
        
        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set OTP expiry to 10 minutes from now
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP via email
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Verify Your Email - Henzo Sushi');
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        // Check if user is already verified (shouldn't happen, but safety check)
        if ($user->email_verified_at) {
            // User already verified, just log them in if not already
            if (!Auth::check() || Auth::id() !== $user->id) {
                Auth::login($user);
            }
            return response()->json([
                'success' => true,
                'message' => 'Email already verified!',
                'redirect' => route('home')
            ]);
        }

        // Check if OTP is valid and not expired
        if (!$user->otp_code || 
            $user->otp_code !== $request->otp || 
            $user->otp_expires_at < now()) {
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        // Verify the user's email - use fresh() to ensure we get the latest state
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Refresh the user model to ensure changes are persisted
        $user->refresh();

        // Clear the email from session since verification is complete
        $request->session()->forget('email');

        // Log the user in and regenerate session to ensure proper authentication
        Auth::login($user);
        $request->session()->regenerate();

        // Send welcome email with promo code after OTP verification
        // Only send to customers (not admin, chef, delivery)
        $skipOtpRoles = ['admin', 'chef', 'delivery'];
        if (!$user->hasAnyRole($skipOtpRoles)) {
            try {
                Mail::to($user->email)->send(new FirstOrderPromoMail());
                \Log::info('Welcome email sent after OTP verification', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email after OTP verification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
        }

        \Log::info('OTP verified successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'redirect' => route('home')
        ]);
    }

    public function resendOtp(Request $request)
    {
        return $this->sendOtp($request);
    }
}