<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Organisations;
use App\Models\OrganisationTypes;
use App\Repositories\Contracts\OrganisationTypesRepositoryInterface;

class OrganisationTypesRepository implements OrganisationTypesRepositoryInterface
{
    public function get_organisation_types()
    {
        return OrganisationTypes::all();
    }

    public function store_organisation_type($name)
    {
        return OrganisationTypes::create($name);
    }
    public function get_organisation_type($id)
    {
        return OrganisationTypes::findOrFail($id);
    }
    public function update_organisation_type($id, $name)
    {
        $orgType = OrganisationTypes::findOrFail($id);
        $orgType->update($name);
        return $orgType;
    }
    public function delete_organisation_type($id)
    {
        $orgType = OrganisationTypes::findOrFail($id);
        $organisation_ids = Organisations::where('type', $id)->pluck('id')->toArray();
        User::whereIn('organisation_id', $organisation_ids)->delete();
        Organisations::where('type', $id)->delete();
        $orgType->delete();
        return $orgType;
    }
}

?>