<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'password',
            'group_role',
            'is_active',
            'is_delete',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->password,
            $user->group_role,
            $user->is_active ? 'Đang hoạt động' : 'Tạm khóa',
            $user->is_delete ? 'Đã xóa' : 'Bình thường',
        ];
    }
}
