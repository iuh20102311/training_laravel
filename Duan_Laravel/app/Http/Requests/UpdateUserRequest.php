<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends UserRequest
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['email'] .= '|unique:users,email,' . $this->user->id;
        $rules['group_role'] = 'required|in:Editor,Reviewer';
        $rules['is_active'] = 'required|boolean';
        
        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();
        $messages['email.unique'] = 'Địa chỉ email đã được sử dụng.';
        $messages['group_role.required'] = 'Vai trò người dùng là bắt buộc.';
        $messages['group_role.in'] = 'Vai trò người dùng không hợp lệ.';
        $messages['is_active.required'] = 'Trạng thái hoạt động là bắt buộc.';
        $messages['is_active.boolean'] = 'Trạng thái hoạt động không hợp lệ.';
        
        return $messages;
    }
}