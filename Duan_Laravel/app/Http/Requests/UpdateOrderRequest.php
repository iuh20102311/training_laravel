<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'user_name' => 'required_if:user_id,null|string|max:255',
            'user_email' => 'required_if:user_id,null|email|max:255',
            'payment_method' => 'required|in:1,2', // Assuming 1 is COD and 2 is PayPal
            'discount_code_id' => 'nullable|exists:discount_codes,id',
            'new_user_checkbox' => 'sometimes|boolean',

            'shipping_addresses' => 'required|array|min:1',
            'shipping_addresses.*.phone_number' => 'required|string|max:20',
            'shipping_addresses.*.city' => 'required|string|max:100',
            'shipping_addresses.*.district' => 'required|string|max:100',
            'shipping_addresses.*.ward' => 'required|string|max:100',
            'shipping_addresses.*.address' => 'required|string|max:255',
            'shipping_addresses.*.ship_charge' => 'required|numeric|min:0',

            'shipping_addresses.*.products' => 'required|array|min:1',
            'shipping_addresses.*.products.*.product_id' => 'required|exists:products,product_id',
            'shipping_addresses.*.products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'user_name.required_if' => 'Tên người dùng là bắt buộc.',
            'user_email.required_if' => 'Email người dùng là bắt buộc.',
            'shipping_addresses.required' => 'Cần ít nhất một địa chỉ giao hàng.',
            'shipping_addresses.*.products.required' => 'Mỗi địa chỉ giao hàng cần ít nhất một sản phẩm.',
            'shipping_addresses.*.products.*.quantity.min' => 'Số lượng sản phẩm phải lớn hơn 0.',
            'shipping_addresses.*.ship_charge.required' => 'Tiền giao hàng là bắt buộc',
            'shipping_addresses.*.address.required' => 'Địa chỉ là bắt buộc phải nhập',
            'shipping_addresses.*.city.required' => 'Thành phố là bắt buộc phải nhập',
            'shipping_addresses.*.district.required' => 'Quận/Huyện là bắt buộc phải nhập',
            'shipping_addresses.*.ward.required' => 'Phường là bắt buộc phải nhập',
            'shipping_addresses.*.phone_number' => 'Số điện thoại là bắt buộc phải nhập',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert checkbox value to boolean
        if ($this->has('new_user_checkbox')) {
            $this->merge([
                'new_user_checkbox' => $this->new_user_checkbox === 'on' || $this->new_user_checkbox === '1',
            ]);
        }
    }
}
