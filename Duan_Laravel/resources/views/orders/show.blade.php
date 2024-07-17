<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Chi tiết đơn hàng') }} {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-2 px-4 py-4">
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

        <div class="product-details-section mt-6">
            @foreach($order->orderDetails as $detail)
                <div class="p-6 bg-white shadow-md rounded-lg mb-4">
                    <div class="flex items-center">
                        <img src="{{ $detail->product->image_url ?? asset('images/placeholder.jpg') }}"
                            alt="{{ $detail->product_name }}" class="w-48 h-48 object-cover mr-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-blue-500">{{ $detail->product_name }}</h4>
                            <p class="text-gray-600">Giá: {{ number_format($detail->price_buy) }} VND</p>
                            <p class="text-gray-600">Số lượng: {{ $detail->quantity }}</p>
                            <p class="text-gray-600">Tổng: {{ number_format($detail->price_buy * $detail->quantity) }} VND
                            </p>

                            <div class="mt-4">
                                <h5 class="text-md font-semibold mb-2 text-blue-500">Địa chỉ giao hàng:</h5>
                                @php
                                    $shippingAddress = $detail->shippingAddress; // Lấy địa chỉ giao hàng từ orderDetail
                                @endphp

                                @if ($shippingAddress)
                                    <p>{{ $shippingAddress->name }}</p>
                                    <p>{{ $shippingAddress->address }}</p>
                                    <p>{{ $shippingAddress->ward }}, {{ $shippingAddress->district }},
                                        {{ $shippingAddress->city }}
                                    </p>
                                    <p>Số điện thoại: {{ $shippingAddress->phone_number }}</p>
                                @else
                                    <p class="text-red-500">Không tìm thấy địa chỉ giao hàng</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="summary-section p-6 bg-white shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Tổng kết</h3>
            <span class="font-bold text-lg text-red-600">{{ number_format($order->total) }} VND</span>
        </div>
    </div>

    <div class="mt-6 mb-6 text-center">
        <a href="{{ route('orders.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-4 px-4 rounded">
            Quay lại danh sách đơn hàng
        </a>
    </div>
</x-app-layout>