<?php
namespace App\Repositories\Contracts\API;

interface OrganisationRepositoryInterface {
    public function organisation_types();
    public function getOrganisations($id);
    public function get_countries();
    public function get_provinces($id);
    public function get_cities($id);
}

?>