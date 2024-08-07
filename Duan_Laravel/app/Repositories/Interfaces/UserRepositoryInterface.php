<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function all(Request $request, $perPage);
    public function createUser(array $data);
    public function updateUser(User $user, array $data);
    public function deleteUser(User $user);
    public function toggleUserActive(User $user);
    public function getExportQuery(Request $request);
}