<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Contracts\UserRepositoryInterface;
use Brian2694\Toastr\Facades\Toastr;

class UsersController extends Controller
{
    protected $userRepo;
    public function __construct(UserRepositoryInterface $userRepo){
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $filters = request()->only([
            'email',
            'status',
            'age',
            'organization',
            'city',
            'province',
            'class',
            'gender',
        ]);

        $users = $this->userRepo->getusers($filters);
        $data = $this->userRepo->get_create_data();

        return view('users.index', compact('users', 'data', 'filters'));
    }

    public function create(){
        $data = $this->userRepo->get_create_data();
        return view('users.create', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|string|max:15|unique:users,number',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'organisation_type' => 'required|exists:organisation_types,id',
            'organisation' => 'required|exists:organisations,id',
            'cnic' => 'required|digits_between:13,15',
            'dob' => 'required|date',
            'class' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'hobbies' => 'required|string',
            'sports_played' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_email' => 'required|email',
            'guardian_phone' => 'required|string|max:15',
            'guardian_dob' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'company_code' => 'nullable',
        ]);


        $this->userRepo->store_user($validated);
        Toastr::success('User created successfully', 'Success');
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = $this->userRepo->get_user($id);
        if(!$user){
        Toastr::error('User not found', 'Error');
        return redirect()->route('users.index');
        }
        $data = $this->userRepo->get_create_data();
        return view('users.edit', compact('user', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'number' => 'required|string|max:15|unique:users,number,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'organisation_type' => 'required|exists:organisation_types,id',
            'organisation' => 'required|exists:organisations,id',
            'cnic' => 'required|digits_between:13,15',
            'dob' => 'required|date',
            'class' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female',
            'hobbies' => 'required|string',
            'sports_played' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_email' => 'required|email',
            'guardian_phone' => 'required|string|max:15',
            'guardian_dob' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
        ]);
        $this->userRepo->update_user($validated, $id);
        Toastr::success('User updated successfully', 'Success');
        return redirect()->route('users.index');
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->userRepo->delete_user($request->id);
        if(!$user){
            Toastr::error('User not found', 'Error');
            return redirect()->route('users.index');
        }
        Toastr::success('User deleted successfully', 'Success');
        return redirect()->route('users.index');
    }

    public function toggleStatus($id)
    {
        if (!Schema::hasColumn('users', 'status')) {
            Toastr::error('The users.status column does not exist yet.', 'Error');
            return redirect()->route('users.index');
        }

        $user = $this->userRepo->get_user($id);

        if (!$user) {
            Toastr::error('User not found', 'Error');
            return redirect()->route('users.index');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        Toastr::success("User marked as {$user->status}.", 'Success');
        return redirect()->route('users.index');
    }

}
