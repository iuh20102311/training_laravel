<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $validator = Validator::make($row->toArray(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'group_role' => 'required|in:Admin,Editor,Reviewer',
                'is_active' => 'required|in:0,1',
                'is_delete' => 'required|in:0,1',
            ], [
                'name.required' => 'Tên là bắt buộc.',
                'name.string' => 'Tên phải là chuỗi.',
                'name.max' => 'Tên không được vượt quá 255 ký tự.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email đã tồn tại trong hệ thống.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'group_role.required' => 'Vai trò nhóm là bắt buộc.',
                'group_role.in' => 'Vai trò nhóm phải là Admin, Editor hoặc Reviewer.',
                'is_active.required' => 'Trạng thái hoạt động là bắt buộc.',
                'is_active.boolean' => 'Trạng thái hoạt động phải là 0:Không hoạt động hoặc 1:Hoạt động.',
                'is_delete.required' => 'Trạng thái xóa là bắt buộc.',
                'is_delete.boolean' => 'Trạng thái xóa phải là 0:Bình thường hoặc 1:Đã xóa.',
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $row->toArray(),
                    'errors' => $validator->errors()->toArray(),
                    'row_number' => $index + 2 // +2 vì dòng đầu tiên là tiêu đề và Excel bắt đầu từ 1
                ];
            } else {
                User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'group_role' => $row['group_role'],
                    'is_active' => $row['is_active'],
                    'is_delete' => $row['is_delete'],
                ]);
            }
        }
    }
}