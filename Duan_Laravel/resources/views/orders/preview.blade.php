<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Xem lại đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Hiển thị thông tin đơn hàng -->
                <h3 class="text-xl font-semibold mb-4">Thông tin người đặt</h3>
                <p>Họ tên: {{ $user->name }}</p>
                <p>Email: {{ $user->email }}</p>
                <p>Số điện thoại: {{ $validatedData['phone_number'] }}</p>

                <h3 class="text-xl font-semibold mt-6 mb-4">Chi tiết đơn hàng</h3>
                @foreach($cart as $productId => $item)
                    <div class="mb-4 pb-4 border-b">
                        <p>{{ $item['name'] }} - Số lượng: {{ $item['quantity'] }}</p>
                        <p>Giá: {{ number_format($item['price']) }} VND</p>
                        <p>Địa chỉ giao hàng:
                            @if($validatedData['shipping_addresses'][$productId]['type'] == 'existing')
                                {{ $user->addresses->find($validatedData['shipping_addresses'][$productId]['address_id'])->full_address }}
                            @else
                                {{ $validatedData['shipping_addresses'][$productId]['address'] }},
                                {{ $validatedData['shipping_addresses'][$productId]['ward'] }},
                                {{ $validatedData['shipping_addresses'][$productId]['district'] }},
                                {{ $validatedData['shipping_addresses'][$productId]['city'] }}
                            @endif
                        </p>
                        <p>Phí vận chuyển:
                            {{ number_format($validatedData['shipping_addresses'][$productId]['ship_charge']) }} VND</p>
                    </div>
                @endforeach

                <div class="mt-6">
                    <p>Tạm tính: {{ number_format($sub_total) }} VND</p>
                    <p>Thuế: {{ number_format($tax) }} VND</p>
                    <p>Phí vận chuyển: {{ number_format($ship_charge) }} VND</p>
                    @if($discount_amount > 0)
                        <p>Giảm giá: {{ number_format($discount_amount) }} VND</p>
                    @endif
                    <p class="font-semibold">Tổng cộng: {{ number_format($total) }} VND</p>
                </div>

                <form method="POST" action="{{ route('orders.place') }}">
                    @csrf
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Xác nhận đặt hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
