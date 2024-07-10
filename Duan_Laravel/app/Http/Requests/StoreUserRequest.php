<?php

namespace App\Http\Requests;

class StoreUserRequest extends UserRequest
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['email'] = 'required|string|email|max:255|unique:users';
        $rules['password'] = 'required|string|min:8|confirmed'; 
        $rules['group_role'] = 'required|string|in:Admin,Editor,Reviewer';

        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();
        $messages['email.unique'] = 'Địa chỉ email đã được sử dụng.';
        $messages['password.required'] = 'Mật khẩu là bắt buộc.';
        $messages['password.min'] = 'Mật khẩu phải có ít nhất 8 ký tự.';
        $messages['password.confirmed'] = 'Mật khẩu không khớp.'; 

        return $messages;
    }
 
}
