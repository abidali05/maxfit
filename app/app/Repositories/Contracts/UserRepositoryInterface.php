<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create();
    public function login(array $data): User;
    public function profile();
    public function profile_update(array $data);
    public function user_profile_update(array $data);
    public function getusers(array $filters = []);
    public function get_create_data();
    public function store_user(array $data);
    public function get_user($id);
    public function update_user(array $data, $id);
    public function delete_user($id);
}
