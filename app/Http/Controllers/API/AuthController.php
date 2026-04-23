<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\API\AuthRepositoryInterface;


class AuthController extends Controller
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
            'number' => 'required|string|unique:users,number',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'terms_conditions' => 'required|boolean',
            "country" => 'required',
            "city" => 'required',
            "state_province" => 'required',
            "branch_id" => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        $data = $validator->validated();

        $user = $this->authRepo->register($data);

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'is_verified' => false
        ]);

        Mail::raw("Your OTP is: $otp. It will expire in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Signup OTP');
        });

        $token = $user->createToken('sanctum')->plainTextToken;

        return $this->success(
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'otp_sent' => true,
                'message' => 'OTP sent to your email. Please verify.'
            ],
            'Signup Successful',
            200
        );
    }

    public function resendOtp(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->notFound('User not found.');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Update OTP in DB
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'is_verified' => false
        ]);

        // Send OTP Email
        Mail::raw("Your OTP is: $otp. It will expire in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Verification OTP');
        });

        return $this->success(
            [
                'otp_sent' => true,
                'message' => 'OTP has been resent to your email.'
            ],
            'OTP Resent',
            200
        );
    }


    public function verifyOtp(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check OTP
        if ($user->otp != $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Check if OTP expired
        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Generate Sanctum token (login)
        $token = $user->createToken('sanctum')->plainTextToken;

        // Response for Flutter
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:users,email',
            'number' => 'nullable|string|exists:users,number',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        // Ensure at least one login field is provided
        if (!$request->filled('email') && !$request->filled('number')) {
            return $this->unprocessable([
                'login' => ['Either email or phone number is required']
            ], 'Validation Error');
        }

        $credentials = $validator->validated();

        // Decide login field
        if (!empty($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->first();
        } else {
            $user = User::where('number', $credentials['number'])->first();
        }

        // $user->token = $user->createToken('auth:sanctum')->plainTextToken;

        // if (!$user || Hash::check($credentials['password'], $user->password)) {
        //     return $this->error('Invalid credentials', [], 401);
        // }
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->error('Invalid credentials', [], 401);
        }

        $user->token = $user->createToken('auth:sanctum')->plainTextToken;

        return $this->success($user, 'Login Successful', 200);
    }


    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return $this->unauthorized('Unauthorized', [], 401);
        }
        $this->authRepo->logout($user);
        return $this->success([], 'Logout Successfull', 200);
    }

    public function profile(Request $request)
    {
        $user = $this->authRepo->profile();

        if (!$user) {
            return $this->unauthorized('Unauthorized', [], 401);
        }

        $isAgeChanged = false;
        $currentAge = null;

        if ($user->dob) {
            $dob = Carbon::parse($user->dob);
            $currentAge = $dob->age;

            // Set genz type
            $genz = $currentAge < 14 ? 'Mother Fit' : 'Father Fit';
            $updates = [];

            if ($user->genz !== $genz) {
                $updates['genz'] = $genz;
            }

            $ageAtRegistration = $user->age_at_registration;
            if ($ageAtRegistration === null && $user->created_at) {
                $ageAtRegistration = $dob->copy()->diffInYears(Carbon::parse($user->created_at));
            }

            if ($ageAtRegistration !== null) {
                $hasCrossedToFatherFit = ((int) $ageAtRegistration) < 14 && $currentAge >= 14;
                $isAgeChanged = $hasCrossedToFatherFit;

                if ($hasCrossedToFatherFit && !$user->profile_updated_for_father_fit) {
                    $updates['assessment'] = false;
                    $updates['goal_setting'] = false;
                    $updates['profile_updated_for_father_fit'] = true;
                }
            }

            if (!empty($updates)) {
                User::where('id', $user->id)->update($updates);
                $user->forceFill($updates);
            }
        }

        // Response-only fields (do not persist)
        $user->current_age = $currentAge;
        $user->age = $currentAge;
        $user->is_age_changed = $isAgeChanged;

        return $this->success($user, 'Profile fetched successfully', 200);
    }

    public function getBranches()
    {
        $branches = Branch::all();
        return $this->success($branches, 'Branches fetched successfully', 200);
    }
}
