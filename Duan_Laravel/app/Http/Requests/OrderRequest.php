<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'discount_code_id' => 'nullable|exists:discount_codes,id',
            'user_id' => 'required|exists:users,id',
            'order_number' => 'string|unique:orders,order_number,' . $this->route('order'),
            'user_name' => 'string|max:255',
            'user_email' => 'email|max:255',
            'phone_number' => 'required|string|max:20|min:9',
            'order_date' => 'date',
            'order_status' => [Rule::in([0, 1, 2])], // 3 trạng thái là 0, 1, 2
            'payment_method' => 'required|string|in:1,2',
            'shipment_date' => 'nullable|date|after_or_equal:order_date',
            'cancel_date' => 'nullable|date|after_or_equal:order_date',
            'sub_total' => 'numeric|min:0',
            'total' => 'numeric|min:0',
            'tax' => 'numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_code' => 'nullable|string|max:50',

            // Các trường liên quan đến sản phẩm và địa chỉ giao hàng
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_addresses' => 'required|array|min:1',
            'shipping_addresses.*.type' => 'required|in:existing,new',
            'shipping_addresses.*.ship_charge' => 'required|numeric|min:10000',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Vui lòng chọn khách hàng.',
            'user_id.exists' => 'Khách hàng không tồn tại.',
            'order_number.required' => 'Số đơn hàng là bắt buộc.',
            'order_number.unique' => 'Số đơn hàng đã tồn tại.',
            'user_email.email' => 'Email không hợp lệ.',
            'phone_number.required' => 'Số điện thoại là bắt buộc.',
            'phone_number.min' => 'Số điện thoại có ít nhất 9 kí tự.',
            'order_date.required' => 'Ngày đặt hàng là bắt buộc.',
            'order_status.required' => 'Trạng thái đơn hàng là bắt buộc.',
            'order_status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'payment_method.required' => 'Phương thức thanh toán là bắt buộc.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'shipment_date.after_or_equal' => 'Ngày giao hàng phải sau hoặc bằng ngày đặt hàng.',
            'cancel_date.after_or_equal' => 'Ngày hủy đơn phải sau hoặc bằng ngày đặt hàng.',
            'sub_total.required' => 'Tổng phụ là bắt buộc.',
            'sub_total.min' => 'Tổng phụ phải lớn hơn hoặc bằng 0.',
            'total.required' => 'Tổng cộng là bắt buộc.',
            'total.min' => 'Tổng cộng phải lớn hơn hoặc bằng 0.',
            'tax.min' => 'Thuế phải lớn hơn hoặc bằng 0.',
            'discount_amount.min' => 'Số tiền giảm giá phải lớn hơn hoặc bằng 0.',
            'products.required' => 'Đơn hàng phải có ít nhất một sản phẩm.',
            'products.min' => 'Đơn hàng phải có ít nhất một sản phẩm.',
            'shipping_addresses.required' => 'Phải có ít nhất một địa chỉ giao hàng.',
            'shipping_addresses.min' => 'Phải có ít nhất một địa chỉ giao hàng.',
            'shipping_addresses.*.ship_charge.min' => 'Phí giao hàng ít nhất là 10000.',
        ];
    }
}
