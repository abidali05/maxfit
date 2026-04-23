<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\API\AuthRepositoryInterface;
use App\Repositories\Contracts\API\OrganisationRepositoryInterface;

class PersonalInfoController extends Controller
{
    protected $orgRepo;
    protected $authRepo;

    public function __construct(OrganisationRepositoryInterface  $orgRepo, AuthRepositoryInterface $authRepo)
    {
        $this->orgRepo = $orgRepo;
        $this->authRepo = $authRepo;
    }

    public function getOrganisationTypes()
    {
        $organisationTypes = $this->orgRepo->organisation_types();
        return $this->success($organisationTypes, 'Organisation Types fetched successfully', 200);
    }

    public function getOrganisations($id)
    {
        $organisations = $this->orgRepo->getOrganisations($id);
        return $this->success($organisations, 'Organisations fetched successfully', 200);
    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            //'email' => 'required|email|unique:users,email,' . auth('sanctum')->user()->id,
            //'password' => 'nullable|string|min:6|confirmed|regex:/[^A-Za-z0-9]/',
            //'number' => 'required|string',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cnic' => 'required|numeric',
            'dob' => 'required|date|before_or_equal:today',
            'organisation_type' => 'required|numeric|exists:organisation_types,id',
            'organisation_id' => 'required|numeric|exists:organisations,id',
            'class' => 'required|string',
            'hobbies' => 'nullable|string',
            'sports_played' => 'nullable|string',
            'guardian_name' => 'required|string',
            'guardian_email' => 'nullable|email',
            'guardian_number' => 'nullable|string',
            'address' => 'required|string',
            'guardian_cnic' => 'required|string',
            // 'country' => 'required|numeric|exists:countries,id',
            // 'state_province' => 'required|numeric|exists:provinces,id',
            // 'city' => 'required|numeric|exists:cities,id',
        ]);


        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        $data = $validator->validated();

        $userdata = $this->authRepo->upateProfile($data);

        if (!$userdata) {
            return $this->unauthorized('Unauthorized', [], 401);
        }
        return $this->success($userdata, 'Profile updated successfully', 200);
    }

    public function physical_assessment(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'height_cm' => 'required|numeric',
            'weight_kg' => 'required|numeric',
            'bmi' => 'required|numeric',
            'gender' => 'required|string',
            'body_shape' => 'required|string',
            'required_body_shape' => 'required|string',
            'exercise_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        $data = $validator->validated();

        $userdata = $this->authRepo->physicalAssessment($data);

        if (!$userdata) {
            return $this->unauthorized('Unauthorized', [], 401);
        }
        return $this->success($userdata, 'Physical assessment added successfully', 200);
    }

    public function get_countries()
    {
        $countries = $this->orgRepo->get_countries();
        return $this->success($countries, 'Countries fetched successfully', 200);
    }
    public function get_provinces($id)
    {
        $provinces = $this->orgRepo->get_provinces($id);
        return $this->success($provinces, 'Provinces fetched successfully', 200);
    }
    public function get_cities($id)
    {
        $cities = $this->orgRepo->get_cities($id);
        return $this->success($cities, 'Cities fetched successfully', 200);
    }
}
