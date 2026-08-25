<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\OrganisationRepositoryInterface;
use Brian2694\Toastr\Facades\Toastr;

class OrganisationController extends Controller
{
    protected $org;
    public function __construct(OrganisationRepositoryInterface $org){
        $this->org = $org;
    }
    public function index()
    {
        $organisations = $this->org->get_organisations();
        return view('organisations.index', compact('organisations'));
    }

    public function create()
    {
        $types = $this->org->get_organisation_types();
        return view('organisations.create', compact('types'));
    }
    public function store(Request $request)
    {
        $data =  $request->validate([
            'name' => 'required|string|max:100|unique:organisations,name',
            'type' => 'required|exists:organisation_types,id',
        ]);

        $this->org->create_organisation($data);
        Toastr::success('Organisation created successfully', 'Success');
        return redirect()->route('organisations.index');
    }

    public function edit($id)
    {
        $organisation = $this->org->get_organisation($id);
        if(!$organisation){
            Toastr::error('Organisation not found', 'Error');
            return redirect()->route('organisations.index');
        }
        $types = $this->org->get_organisation_types();
        return view('organisations.edit', compact('organisation', 'types'));
    }

    public function update(Request $request, $id)
    {
        $organisation = $this->org->get_organisation($id);
        if(!$organisation){
            Toastr::error('Organisation not found', 'Error');
            return redirect()->route('organisations.index');
        }
        $data =  $request->validate([
            'name' => 'required|string|max:100|unique:organisations,name,'.$organisation->id,
            'type' => 'required|exists:organisation_types,id',
        ]);

        $this->org->update_organisation($id, $data);
        Toastr::success('Organisation updated successfully', 'Success');
        return redirect()->route('organisations.index');
    }
    public function destroy($id)
    {
        $organisation = $this->org->get_organisation($id);
        if(!$organisation){
            Toastr::error('Organisation not found', 'Error');
            return redirect()->route('organisations.index');
        }
        $this->org->delete_organisation($id);
        Toastr::success('Organisation deleted successfully', 'Success');
        return redirect()->route('organisations.index');
    }
}
