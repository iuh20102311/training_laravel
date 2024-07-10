<?php

namespace App\Http\Requests;

class StoreProductRequest extends ProductRequest
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['name'] = 'required|string|min:5'; 
        
        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();
        $messages['name.min'] = 'Tên sản phẩm phải có ít nhất 5 ký tự.';
        
        return $messages;
    }
}
