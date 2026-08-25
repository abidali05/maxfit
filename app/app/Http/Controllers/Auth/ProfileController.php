<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Coach;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\UserRepositoryInterface;

class ProfileController extends Controller
{
    protected $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function profile()
    {
        $user = $this->userRepo->profile();
        return view('auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (auth()->guard('branch')->check()) {
            // Branch Guard
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'number' => 'required|string|unique:branches,phone,' . auth('branch')->id(),
                'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'required|email|max:255|unique:branches,email,' . auth('branch')->id(),
            ]);

            $user = Branch::findOrFail(auth('branch')->id());
        } elseif (auth()->guard('coach')->check()) {
            // Coach Guard
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'number' => 'required|string|unique:coaches,phone,' . auth('coach')->id(),
                'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'required|email|max:255|unique:coaches,email,' . auth('coach')->id(),
            ]);

            $user = Coach::findOrFail(auth('coach')->id());
        } else {
            // Default user guard
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'number' => 'required|string|unique:users,number,' . auth()->id(),
                'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            ]);

            $user = User::findOrFail(auth()->id());
        }

        // Update image if uploaded
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $data['image'] = $path;
        }

        // Update password if provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        Toastr::info('Profile updated successfully!', 'Success');
        return redirect()->back();
    }
}
