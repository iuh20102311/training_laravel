<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function all(Request $request, $perPage)
    {
        $query = User::where('is_delete', 0);

        $filters = [
            'is_active' => $request->is_active,
            'name' => $request->name,
            'email' => $request->email,
            'group_role' => $request->group_role,
        ];

        if ($filters['name']) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if ($filters['email']) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if ($filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($filters['group_role']) {
            $query->where('group_role', $filters['group_role']);
        }

        return [
            'query' => $query,
            'filters' => $filters,
            'users' => $query->orderByDesc('created_at')->paginate($perPage)->appends($filters)
        ];
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user)
    {
        return $user->update(['is_delete' => 1]);
    }

    public function toggleUserActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return $user;
    }

    public function getExportQuery(Request $request)
    {
        $query = User::where('is_delete', 0);

        $filters = [
            'is_active' => $request->is_active,
            'name' => $request->name,
            'email' => $request->email,
            'group_role' => $request->group_role,
        ];

        if ($filters['name']) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if ($filters['email']) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if ($filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($filters['group_role']) {
            $query->where('group_role', $filters['group_role']);
        }

        return $query;
    }
}
