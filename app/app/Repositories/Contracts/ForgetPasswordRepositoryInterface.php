<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface ForgetPasswordRepositoryInterface
{
    public function login(array $data): User;
}
