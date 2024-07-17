<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Chi tiết đơn hàng') }} #{{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="order-info-section p-6 bg-white shadow-md rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-blue-500">Thông tin đơn hàng</h3>
                <div class="space-y-3">
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Khách hàng:</span>
                        <span>{{ $order->user->name }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Email:</span>
                        <span>{{ $order->user_email }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Số điện thoại:</span>
                        <span>{{ $order->phone_number }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Ngày đặt hàng:</span>
                        <span>{{ $order->order_date }}</span>
                    </p>
                    <p class="flex justify-between items-center">
                        <span class="font-semibold text-gray-600">Trạng thái:</span>
                        @if($order->order_status == 0)
                            <span class="px-2 py-1 rounded text-xs font-semibold text-yellow-800 bg-yellow-200">Đang xử
                                lý</span>
                        @elseif($order->order_status == 1)
                            <span class="px-2 py-1 rounded text-xs font-semibold text-green-800 bg-green-200">Đã xác
                                nhận</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-semibold text-red-800 bg-red-200">Đã hủy</span>
                        @endif
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Phương thức thanh toán:</span>
                        <span>{{ $order->payment_method == 1 ? 'COD' : 'PayPal' }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="shipping-address-section p-6 bg-white shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Địa chỉ giao hàng</h3>
            @foreach ($order->shippingAddresses as $address)
                <div class="mb-4">
                    <p class="font-semibold">{{ $address->name }}</p>
                    <p>{{ $address->address }}</p>
                    <p>{{ $address->ward }}, {{ $address->district }}, {{ $address->city }}</p>
                    <p>Số điện thoại: {{ $address->phone_number }}</p>
                </div>
            @endforeach
        </div>

        <div class="product-details-section p-6 bg-white shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Chi tiết sản phẩm</h3>
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-center text-gray-600">Sản phẩm</th>
                        <th class="px-4 py-2 text-center text-gray-600">Giá</th>
                        <th class="px-4 py-2 text-center text-gray-600">Số lượng</th>
                        <th class="px-4 py-2 text-center text-gray-600">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                        <tr class="border-b">
                            <td class="px-4 py-3 text-center">{{ $detail->product_name }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($detail->price_buy) }} VND</td>
                            <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($detail->price_buy * $detail->quantity) }} VND
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section p-6 bg-white shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Tổng kết</h3>
            <span class="font-bold text-lg text-red-600">{{ number_format($order->total) }} VND</span>
        </div>
    </div>

    <div class="mb-6 text-center">
        <a href="{{ route('orders.index') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Quay lại danh sách đơn hàng
        </a>
    </div>
</x-app-layout>