<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['coach'])
            ->withCount('groupUsers')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.groups.index', compact('groups'));
    }

    public function show($id)
    {
        $group = Group::with(['groupUsers.user', 'coach', 'groupExercises.exercise', 'countryRelation'])
            ->findOrFail($id);

        return view('admin.groups.show', compact('group'));
    }

    public function suspend($id)
    {
        $group = Group::findOrFail($id);
        $group->update(['status' => 'suspended']);

        Toastr::success('Athlete Group suspended successfully', 'Success');
        return redirect()->back();
    }

    public function unsuspend($id)
    {
        $group = Group::findOrFail($id);
        $group->update(['status' => 'active']);

        Toastr::success('Athlete Group activated successfully', 'Success');
        return redirect()->back();
    }
}
