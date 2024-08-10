<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Chi tiết đơn hàng') }} {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
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
                                <span
                                    class="order-status-span font-bold bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đang
                                    xử lý</span>
                            @elseif($order->order_status == 1)
                                <span
                                    class="order-status-span font-bold bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đã
                                    xác nhận</span>
                            @elseif($order->order_status == 2)
                                <span
                                    class="order-status-span font-bold bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đã
                                    hủy</span>
                            @endif
                        </p>
                        <p class="flex justify-between">
                            <span class="font-semibold text-gray-600">Phương thức thanh toán:</span>
                            <span>{{ $order->payment_method == 1 ? 'COD' : 'PayPal' }}</span>
                        </p>
                    </div>
                </div>


                <div class="product-details-section mt-6">
                    @foreach($groupedDetails as $addressId => $details)
                        <div class="p-6 bg-white shadow-md rounded-lg mb-6">
                            <h3 class="text-xl font-bold mb-4 text-blue-600">Địa chỉ giao hàng:</h3>
                            @php
                                $shippingAddress = $details->first()->shippingAddress;
                            @endphp
                            @if ($shippingAddress)
                                <div class="mb-4">
                                    <p>{{ $shippingAddress->name }}</p>
                                    <p>{{ $shippingAddress->address }}</p>
                                    <p>{{ $shippingAddress->ward }}, {{ $shippingAddress->district }},
                                        {{ $shippingAddress->city }}</p>
                                    <p>Số điện thoại: {{ $shippingAddress->phone_number }}</p>
                                </div>
                            @else
                                <p class="text-red-500 mb-4">Không tìm thấy địa chỉ giao hàng</p>
                            @endif

                            <h4 class="text-lg font-semibold mb-2 text-blue-500">Sản phẩm:</h4>
                            @foreach($details as $detail)
                                <div class="flex items-center border-t pt-4 mt-4">
                                    <img src="{{ $detail->product->image_url ?? asset('images/placeholder.jpg') }}"
                                        alt="{{ $detail->product_name }}" class="w-32 h-32 object-cover mr-4">
                                    <div>
                                        <h5 class="text-md font-semibold mb-1">{{ $detail->product_name }}</h5>
                                        <p class="text-gray-600">Giá: {{ number_format($detail->price_buy) }} VND</p>
                                        <p class="text-gray-600">Số lượng: {{ $detail->quantity }}</p>
                                        <p class="text-gray-600">Tổng:
                                            {{ number_format($detail->price_buy * $detail->quantity) }} VND</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="summary-section p-6 bg-white shadow-md rounded-lg mt-6">
                    <h3 class="text-lg font-semibold mb-4 text-blue-500">Tổng kết</h3>
                    <span class="font-bold text-lg text-red-600">{{ number_format($order->total) }} VND</span>
                </div>


                <div class="mt-6 mb-6 text-center">
                    <a href="{{ route('orders.index') }}"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-4 px-4 rounded">
                        Quay lại danh sách đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>