<?php

namespace App\Http\Requests;

class CreateOrderRequest extends OrderRequest
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['user_type'] = 'required|in:existing,new';

        $userType = $this->get('user_type');

        if ($userType === 'existing') {
            $rules['user_id'] = 'required|exists:users,id';
        } elseif ($userType === 'new') {
            $rules['new_user_name'] = 'required|string|max:255';
            $rules['new_user_email'] = 'required|email|unique:users,email';
        }

        if ($this->has('shipping_addresses')) {
            foreach ($this->get('shipping_addresses') as $key => $address) {
                if (isset($address['type']) && $address['type'] === 'existing') {
                    $rules["shipping_addresses.$key.address_id"] = 'required|exists:user_addresses,id';
                } elseif (isset($address['type']) && $address['type'] === 'new') {
                    $rules["shipping_addresses.$key.phone_number"] = 'required|string|max:20|min:9';
                    $rules["shipping_addresses.$key.city"] = 'required|string|max:100';
                    $rules["shipping_addresses.$key.district"] = 'required|string|max:100';
                    $rules["shipping_addresses.$key.ward"] = 'required|string|max:100';
                    $rules["shipping_addresses.$key.address"] = 'required|string|max:255';
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = parent::messages();

        return array_merge($messages, [
            'user_type.required' => 'Vui lòng chọn loại khách hàng.',
            'user_id.required' => 'Vui lòng chọn khách hàng.',
            'new_user_name.required' => 'Vui lòng nhập tên khách hàng mới.',
            'new_user_email.required' => 'Vui lòng nhập email khách hàng mới.',
            'new_user_email.unique' => 'Email này đã được sử dụng.',
            'shipping_addresses.*.address_id.required' => 'Vui lòng chọn địa chỉ giao hàng.',
            'shipping_addresses.*.phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'shipping_addresses.*.phone_number.min' => 'Số điện thoại phải có ít nhất 9 số.',
            'shipping_addresses.*.city.required' => 'Vui lòng nhập thành phố.',
            'shipping_addresses.*.district.required' => 'Vui lòng nhập quận/huyện.',
            'shipping_addresses.*.ward.required' => 'Vui lòng nhập phường/xã.',
            'shipping_addresses.*.address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);
    }
}
