<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'name'       => $row['name'],
            'email'      => $row['email'],
            'password'   => Hash::make($row['password']),
            'group_role' => $row['group_role'],
            'is_active'  => $row['is_active'],
            'is_delete'  => $row['is_delete'],
        ]);
    }
}
