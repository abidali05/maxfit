<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DailyAssessment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class ProfileController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function updateProfile(Request $request)
    {
        try {
            // Get the current user by their ID
            $user = User::findOrFail($request->user_id);

            // Validate the request data
            $data = $request->validate([
                'name' => 'nullable|string|max:255',
                'number' => 'nullable|string|max:15|unique:users,number,' . $user->id,
                'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            // Call the userRepo method to update the user profile
            $user = $this->userRepo->user_profile_update($data);

            // Return success response if user is updated
            return $this->success($user, 'User updated successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle user not found exception
            return $this->error('User not found.', ['user_id' => ['The user with the provided ID was not found.']], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return $this->error('Validation failed.', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Something went wrong: ' . $e->getMessage(), [], 500);
        }
    }


    public function checkUserName(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $usernameExists = User::where('username', $request->username)->exists();

        if ($usernameExists) {
            return response()->json([
                'message' => 'Username is already taken.',
                'status' => 'error',
            ], 400);
        }

        return response()->json([
            'message' => 'Username is available.',
            'status' => 'success',
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[^A-Za-z0-9]/'
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect.',
            ], 403);
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


    public function daily_assesments_index(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'assessments' => 'required|array',
            'assessments.*.set_id' => 'required|exists:sets,id',
            'assessments.*.exercise_id' => 'required|exists:exercises,id',
            'assessments.*.count' => 'required|integer|min:0',
        ]);

        $savedAssessments = [];

        foreach ($request->assessments as $assessmentData) {
            $savedAssessments[] = DailyAssessment::create([
                'user_id' => $user->id,
                'set_id' => $assessmentData['set_id'],
                'exercise_id' => $assessmentData['exercise_id'],
                'count' => $assessmentData['count'],
            ]);
        }

        $user->update([
            'assessment' => 1
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Daily assessments saved successfullyzszdsad',
            'data' => $savedAssessments,
        ]);
    }


    public function daily_assesments(Request $request, $id)
    {
        try {
            $assessments = DailyAssessment::with(['user', 'set', 'exercise'])
                ->where('user_id', $id)
                ->get();

            if ($assessments->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'data' => null,
                    'message' => 'No daily assessments found for this user.'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $assessments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }


    public function getAllUsers()
    {
        $loggedInUser = Auth::user();

        $users = User::with('physical_assessment')
            ->where('role', 'user')
            ->where('id', '!=', $loggedInUser->id)
            ->get();

        $loggedAge = Carbon::parse($loggedInUser->dob)->age;

        $allowedGenz = ($loggedAge < 14)
            ? ['motherfits']
            : ['fatherfits'];

        $loggedInUser->genz = $allowedGenz;

        foreach ($users as $u) {
            $age = Carbon::parse($u->dob)->age;
            $u->age = $age;
            $u->genz = ($age < 14)
                ? ['motherfits']
                : ['fatherfits'];
        }

        $final_users = $users->filter(function ($u) use ($allowedGenz) {
            return !empty(array_intersect($u->genz, $allowedGenz));
        });

        return $this->success(
            $final_users->values(),
            'same category users fetched successfully',
            200
        );
    }
}
