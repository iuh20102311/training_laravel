<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            @if($order)
                {{ __('Thông tin đơn hàng') }} {{ $order->order_number }}
            @else
                <p>{{ __('Chưa có đơn hàng') }}</p>
            @endif
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="order-info-section p-6 bg-white shadow-md rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Thông tin đơn hàng</h3>
            <div class="space-y-3">
                <p class="flex justify-between">
                    <span class="font-semibold text-gray-600">Khách hàng:</span>
                    <span>{{ $user->user_name }}</span>
                </p>
                <p class="flex justify-between">
                    <span class="font-semibold text-gray-600">Email:</span>
                    <span>{{ $user->user_email }}</span>
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

    @if($order)
        <div class="container mx-auto mt-4 px-4 py-4">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-100 text-left">Sản phẩm</th>
                        <th class="px-4 py-2 bg-gray-100 text-center">Số lượng</th>
                        <th class="px-4 py-2 bg-gray-100 text-right">Giá</th>
                        <th class="px-4 py-2 bg-gray-100 text-right">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                        <tr>
                            <td class="border px-4 py-2">{{ $detail->product->name }}</td>
                            <td class="border px-4 py-2 text-center">{{ $detail->quantity }}</td>
                            <td class="border px-4 py-2 text-right">${{ number_format($detail->price_buy, 2) }}</td>
                            <td class="border px-4 py-2 text-right">
                                ${{ number_format($detail->quantity * $detail->price_buy, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="border px-4 py-2 text-right font-bold">Tổng cộng</td>
                        <td class="border px-4 py-2 text-right font-bold">${{ number_format($order->orderDetails->sum(function ($detail) {
            return $detail->quantity * $detail->price_buy; }), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <p>Không có đơn hàng nào đang xử lý.</p>
    @endif
</x-app-layout>