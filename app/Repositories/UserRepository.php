<?php

namespace App\Repositories;

use App\Models\User;
use App\Mail\LoginAlertMail;
use App\Models\City;
use App\Models\Branch;
use App\Models\Organisations;
use App\Models\OrganisationTypes;
use App\Models\Province;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function create()
    {
        return view('auth.login');
    }
    public function login(array $data): User
    {
        $user = User::where('email', $data['email'])->first();

        if ($user && Auth::attempt(['email' => $data['email'], 'password' => $data['password']]) && $user->role == 'admin') {

            // Send email
            $ip = request()->ip();
            $agent = request()->userAgent();
            $browser = get_browser_name($agent);
            $platform = get_os_name($agent);

            if (Mail::to($user->email)->send(new LoginAlertMail($user, $ip, $browser, $platform))) {

                return $user;
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['email not sent'],
                ]);
            }
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function profile()
    {
        return Auth::user();
    }

    public function profile_update($data)
    {
        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->number = $data['number'];
        if (isset($data['image']) && $data['image']->isValid()) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $path = $data['image']->storeAs('uploads/profile_images', $imageName, 'public');
            $user->image = $path;
        }
        $user->save();
        return $user;
    }

    public function user_profile_update($data)
    {
        // Find the user by ID
        $user = User::findOrFail($data['user_id']);

        // Only update name if it is in the request
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        // Only update number if it is in the request
        if (isset($data['number'])) {
            $user->number = $data['number'];
        }

        // Only update username if it is in the request
        if (isset($data['username'])) {
            $user->username = $data['username'];
        }

        // Handle image upload only if an image is provided
        if (isset($data['image']) && $data['image']->isValid()) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Store the new image
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $path = $data['image']->storeAs('uploads/profile_images', $imageName, 'public');
            $user->image = $path;
        }

        // If 'cnic_name' is not updated, keep the original value
        $user->cnic_name = $user->cnic_name;

        // Save the updated user
        $user->save();

        return $user;
    }

    public function getusers(array $filters = [])
    {
        $query = User::query()->with('branch')->where('role', '!=', 'admin');

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['organization'])) {
            $query->where('organisation_id', $filters['organization']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (!empty($filters['province'])) {
            $query->where('state_province', $filters['province']);
        }

        if (!empty($filters['class'])) {
            $query->where('class', $filters['class']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        if (!empty($filters['age'])) {
            $age = (int) $filters['age'];
            $query->whereNotNull('dob')
                ->whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) = ?', [$age]);
        }

        return $query->latest()->get();
    }

    public function get_create_data()
    {
        $data['organizations'] = Organisations::all();
        $data['organisation_types'] = OrganisationTypes::all();
        $data['provinces'] = Province::all();
        $data['cities'] = City::all();
        $data['classes'] = User::whereNotNull('class')->distinct()->orderBy('class')->pluck('class');
        $data['genders'] = User::whereNotNull('gender')->distinct()->orderBy('gender')->pluck('gender');

        return $data;
    }

    public function store_user(array $data)
    {
        // dd($data['company_code']);
        $branch = Branch::where('code',$data['company_code'])->first();
        // dd($branch);
        
        
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->number = $data['number'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'];
        $user->cnic = $data['cnic'];
        $user->branch_name = $branch->name;
        $user->branch_id = $branch->id;
        $user->dob = $data['dob'];
        $user->class = $data['class'];
        $user->gender = $data['gender'];
        $user->hobbies = $data['hobbies'];
        $user->sports_played = $data['sports_played'];
        $user->guardian_name = $data['guardian_name'];
        $user->guardian_email = $data['guardian_email'];
        $user->guardian_number = $data['guardian_phone'];
        $user->dob = $data['guardian_dob'];
        $user->city = $data['city'];
        $user->organisation_type = $data['organisation_type'];
        $user->organisation_id = $data['organisation'];
        $user->state_province = $data['province'];
        $user->country = 166; // Pakistan
        if (isset($data['image']) && $data['image']->isValid()) {
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $path = $data['image']->storeAs('uploads/profile_images', $imageName, 'public');
            $user->image = $path;
        }
        $user->save();
        dd('$data');
    }

    public function get_user($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function update_user(array $data, $id)
    {
        $user = User::find($id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->number = $data['number'];
        $user->role = $data['role'];
        if (isset($data['password']) && !empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->cnic = $data['cnic'];
        $user->dob = $data['dob'];
        $user->class = $data['class'];
        $user->gender = $data['gender'];
        $user->hobbies = $data['hobbies'];
        $user->sports_played = $data['sports_played'];
        $user->guardian_name = $data['guardian_name'];
        $user->guardian_email = $data['guardian_email'];
        $user->guardian_number = $data['guardian_phone'];
        $user->guardian_dob = $data['guardian_dob'];
        $user->city = $data['city'];
        $user->organisation_type = $data['organisation_type'];
        $user->organisation_id = $data['organisation'];
        $user->state_province = $data['province'];
        if (isset($data['image']) && $data['image']->isValid()) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $imageName = time() . '.' . $data['image']->getClientOriginalExtension();
            $path = $data['image']->storeAs('uploads/profile_images', $imageName, 'public');
            $user->image = $path;
        }
        $user->save();
        return $user;
    }

    public function delete_user($id)
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        if ($user->image) {
            $imagePath = str_replace(url('/storage'), 'storage', $user->image);
            $fullPath = public_path($imagePath);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $user->delete();
        return true;
    }
}
