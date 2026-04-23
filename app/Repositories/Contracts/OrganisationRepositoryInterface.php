<?php
namespace App\Repositories\Contracts;
interface OrganisationRepositoryInterface {
    public function get_organisations();
    public function get_organisation_types();
    public function create_organisation(array $data);
    public function get_organisation($id);
    public function update_organisation($id, array $data);
    public function delete_organisation($id);
   
}

?>