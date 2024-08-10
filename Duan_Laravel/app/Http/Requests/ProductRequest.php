<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|max:1000000',
            'description' => 'required',
            'status' => 'required|in:Đang bán,Ngừng bán,Hết hàng',
            'image' =>  'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:max_width=1024'        
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'price.required' => 'Giá bán không được để trống.',
            'price.numeric' => 'Giá bán chỉ được nhập số.',
            'price.regex' => 'Giá bán chỉ được nhập số và không được là số âm.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'price.max' => 'Giá bán tối đa là 1.000.000 VNĐ.',
            'description.required' => 'Mô tả là bắt buộc.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh chỉ được có định dạng jpg, jpeg, png.',
            'image.max' => 'Dung lượng hình không được vượt quá 2Mb.',
            'image.dimensions' => 'Kích thước hình không được vượt quá 1024px.',
        ];
    }
}
