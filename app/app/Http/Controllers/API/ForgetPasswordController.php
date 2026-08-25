<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Contracts\ForgetPasswordRepositoryInterface;

class ForgetPasswordController extends Controller
{
    // protected $userRepo;

    // public function __construct(ForgetPasswordRepositoryInterface $userRepo)
    // {
    //     $this->userRepo = $userRepo;
    // }


    public function sendotp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = random_int(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return response()->json([
            'message' => 'OTP has been sent to your email address.',
            'otp' => $otp
        ], 200);
    }

    public function checkOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            $user &&
            $user->otp === $request->otp &&
            $user->otp_expires_at &&
            now()->lt($user->otp_expires_at)
        ) {
            return response()->json([
                'success' => true,
                'message' => 'OTP is valid.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP.',
        ], 422);
    }

    public function forgetUpdatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[^A-Za-z0-9]/'
            ],
        ]);

$getuser = User::where('email', $request->email)->first();
        $user = User::findOrfail($getuser->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
