<?php

namespace App\Repositories\API;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\Province;
use App\Models\Organisations;
use App\Models\OrganisationTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\API\OrganisationRepositoryInterface;


class OrganisationRepository implements OrganisationRepositoryInterface {
    
    public function organisation_types(){
        return OrganisationTypes::all();
    }

    public function getOrganisations($id){
        return Organisations::where('type', $id)->get();
    }

    public function get_countries(){
        return Country::all();
    }

    public function get_provinces($id){
        return Province::where('country_id', $id)->get();
    }

    public function get_cities($id){
        return City::where('state_id', $id)->get();
    
    }
}