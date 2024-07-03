<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends ProductRequest
{
    public function rules()
    {
        $rules = parent::rules();
        // $rules['email'] .= '|unique:users,email,' . $this->user->id;
        
        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();
        // $messages['email.unique'] = 'Địa chỉ email đã được sử dụng.';
        
        return $messages;
    }
}
