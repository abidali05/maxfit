<?php
namespace App\Repositories\Contracts;
interface OrganisationTypesRepositoryInterface
{
    public function get_organisation_types();
    public function store_organisation_type($name);
    public function get_organisation_type($id);
    public function update_organisation_type($id, $name);
    public function delete_organisation_type($id);
}

?>
   
