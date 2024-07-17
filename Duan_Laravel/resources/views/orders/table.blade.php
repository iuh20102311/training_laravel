<div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
    @if($orders->isEmpty())
        <div class="text-center py-4 text-gray-500">
            Không có dữ liệu
        </div>
    @else
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
            <thead>
                <tr class="text-left">
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Mã khách hàng
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Ngày đặt
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Trạng thái
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Khách hàng
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Tổng tiền
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Thanh toán
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Số điện thoại
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Thao tác
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $order)
                        <tr class="hover:bg-gray-100 {{ $loop->even ? 'bg-red-50' : 'bg-white' }}" id="order-row-{{ $order->id }}">
                            <td class="border-dashed border-t border-red-200 px-6 py-4 text-center whitespace-nowrap">
                                {{ $order->order_number }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center whitespace-nowrap">
                                {{ $order->order_date }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                @if($order->order_status == 0)
                                    <span
                                        class="order-status-span bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đang
                                        xử lý</span>
                                @elseif($order->order_status == 1)
                                    <span
                                        class="order-status-span bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đã
                                        xác nhận</span>
                                @elseif($order->order_status == 2)
                                    <span
                                        class="order-status-span bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs whitespace-nowrap">Đã
                                        hủy</span>
                                @endif
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center whitespace-nowrap">
                                {{ $order->user_name }}
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center whitespace-nowrap">
                                {{ number_format($order->total, 0, ',', '.') }} VND
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                @php
                                    $paymentDetails = $order->getPaymentMethodDetails();
                                @endphp
                                <span
                                    class="payment-method-span {{ $paymentDetails['classes'] }}">{{ $paymentDetails['label'] }}</span>
                            </td>
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center whitespace-nowrap">
                                {{ $order->phone_number }}
                            </td>

                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('orders.show', $order) }}" class="text-green-500 hover:text-green-700"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST"
                                        class="inline update-status-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button"
                                            class="update-status {{ $order->order_status == 0 ? 'text-blue-500 hover:text-blue-700' : 'text-red-500 hover:text-red-700' }}"
                                            data-id="{{ $order->id }}" data-status="{{ $order->order_status }}"
                                            title="{{ $order->order_status == 0 ? 'Xác nhận đơn hàng' : 'Hủy đơn hàng' }}">
                                            <i class="{{ $order->order_status == 0 ? 'fas fa-check' : 'fas fa-times' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('orders.edit', $order) }}" class="text-blue-500 hover:text-blue-700"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                        class="inline delete-order-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-500 hover:text-red-700 delete-order"
                                            data-id="{{ $order->id }}" title="Xóa đơn hàng">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>