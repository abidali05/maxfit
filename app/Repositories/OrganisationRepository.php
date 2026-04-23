<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Organisations;
use App\Models\OrganisationTypes;
use App\Repositories\Contracts\OrganisationRepositoryInterface;

class OrganisationRepository implements OrganisationRepositoryInterface
{
    protected $organisation;
    public function __construct(Organisations $organisation)
    {
        $this->organisation = $organisation;
    }
    public function get_organisations()
    {
        return $this->organisation->with('organisation_type')->get();
    }
    public function get_organisation_types()
    {
        return OrganisationTypes::all();
    }
    public function create_organisation(array $data)
    {
        return $this->organisation->create($data);
    }

    public function get_organisation($id)
    {
        return $this->organisation->with('organisation_type')->findOrFail($id);
    }
    public function update_organisation($id, array $data)
    {
        $organisation = $this->organisation->findOrFail($id);
        return $organisation->update($data);
    }
    public function delete_organisation($id)
    {
        User::where('organisation_id', $id)->delete();
        $organisation = $this->organisation->findOrFail($id);
        return $organisation->delete();
    }
}
