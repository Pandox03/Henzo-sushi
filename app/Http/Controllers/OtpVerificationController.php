<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OtpVerificationController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->get('email') ?: session('email');
        
        \Log::info('OTP verification page accessed', [
            'email_from_request' => $request->get('email'),
            'email_from_session' => session('email'),
            'final_email' => $email
        ]);
        
        // Auto-send OTP if email is provided
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

        // Check if OTP is valid and not expired
        if (!$user->otp_code || 
            $user->otp_code !== $request->otp || 
            $user->otp_expires_at < now()) {
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        // Verify the user's email
        $user->update([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null
        ]);

        // Log the user in
        Auth::login($user);

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