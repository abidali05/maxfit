<?php

namespace App\Repositories\Contracts\API;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $data);
    public function logout($user);
    public function profile();
    public function upateProfile($data);
    public function physicalAssessment($data);
}
