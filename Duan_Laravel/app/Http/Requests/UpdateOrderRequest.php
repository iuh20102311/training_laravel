<?php

namespace App\Http\Requests;

class UpdateOrderRequest extends OrderRequest
{
    public function rules()
    {
        $rules = parent::rules();

        $userType = $this->get('user_type');

        // Quy tắc cho các địa chỉ giao hàng
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
            'shipping_addresses.*.address_id.required' => 'Vui lòng chọn địa chỉ giao hàng.',
            'shipping_addresses.*.address_id.exists' => 'Địa chỉ giao hàng không hợp lệ.',
            'shipping_addresses.*.phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'shipping_addresses.*.phone_number.min' => 'Số điện thoại phải có ít nhất 9 số.',
            'shipping_addresses.*.phone_number.max' => 'Số điện thoại không được quá 20 số.',
            'shipping_addresses.*.city.required' => 'Vui lòng nhập thành phố.',
            'shipping_addresses.*.city.max' => 'Thành phố không được quá 100 ký tự.',
            'shipping_addresses.*.district.required' => 'Vui lòng nhập quận/huyện.',
            'shipping_addresses.*.district.max' => 'Quận/huyện không được quá 100 ký tự.',
            'shipping_addresses.*.ward.required' => 'Vui lòng nhập phường/xã.',
            'shipping_addresses.*.ward.max' => 'Phường/xã không được quá 100 ký tự.',
            'shipping_addresses.*.address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'shipping_addresses.*.address.max' => 'Địa chỉ không được quá 255 ký tự.',
        ]);
    }
}
