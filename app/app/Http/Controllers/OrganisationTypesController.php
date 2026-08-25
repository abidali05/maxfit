<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\OrganisationTypesRepositoryInterface;
use Brian2694\Toastr\Facades\Toastr;


class OrganisationTypesController extends Controller
{
    protected $orgType;
    public function __construct(OrganisationTypesRepositoryInterface $orgType){
        $this->orgType = $orgType;
    }

    public function index()
    {
        $orgTypes = $this->orgType->get_organisation_types();
        return view('organisation_types.index', compact('orgTypes'));
    }

    public function create()
    {
        return view('organisation_types.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:organisation_types,name',
        ]);
        $this->orgType->store_organisation_type($validated);
        Toastr::success('Organisation Type created successfully', 'Success');
        return redirect()->route('organisation-types.index');
    }

    public function edit($id)
    {
        $organisation_type = $this->orgType->get_organisation_type($id);
        if(!$organisation_type){
            Toastr::error('Organisation Type not found', 'Error');
            return redirect()->route('organisation-types.index');
        }
        return view('organisation_types.edit', compact('organisation_type'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:organisation_types,name,'.$id,
        ]);
        $this->orgType->update_organisation_type($id, $validated);
        Toastr::success('Organisation Type updated successfully', 'Success');
        return redirect()->route('organisation-types.index');
    }
    public function destroy($id)
    {
        $organisation_type = $this->orgType->get_organisation_type($id);
        if(!$organisation_type){
            Toastr::error('Organisation Type not found', 'Error');
            return redirect()->route('organisation-types.index');
        }
        $this->orgType->delete_organisation_type($id);
        Toastr::success('Organisation Type deleted successfully', 'Success');
        return redirect()->route('organisation-types.index');
    }
}
