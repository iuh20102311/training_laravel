<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
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
