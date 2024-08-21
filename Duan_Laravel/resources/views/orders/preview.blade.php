<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight">
            {{ __('Xem lại đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <form id="orderForm" method="POST" action="{{ route('orders.place') }}" class="mt-8">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Có lỗi xảy ra!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-8 bg-blue-100 py-6 rounded-lg">
                        <h3 class="text-2xl font-bold mb-4 text-blue-800">
                            <i class="fas fa-user mr-2"></i> Thông tin người đặt
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex flex-col md:flex-row md:items-center w-full">
                                <span class="font-semibold mr-2">Họ tên:</span>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center w-full">
                                <span class="font-semibold mr-2">Email:</span>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center w-full">
                                <span class="font-semibold mr-2">Số điện thoại:</span>
                                <p>{{ $validatedData['phone_number'] }}</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-2xl font-bold mb-6 text-gray-800">
                        <i class="fas fa-shopping-cart mr-2"></i> Chi tiết đơn hàng
                    </h3>
                    @foreach($cart as $productId => $item)
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <div class="flex flex-wrap justify-between items-start">
                                <div class="flex items-center w-full md:w-1/2 mb-4 md:mb-0">
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                        class="w-20 h-20 object-cover rounded-md mr-4 border border-gray-300">
                                    <div>
                                        <h4 class="text-lg font-semibold text-indigo-600">{{ $item['name'] }}</h4>
                                        <p class="text-gray-600">Số lượng: {{ $item['quantity'] }}</p>
                                        <p class="text-gray-800 font-medium">Giá: {{ number_format($item['price']) }} VND
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 flex flex-col">
                                    <p class="text-gray-700">
                                        <span class="font-semibold">
                                            <i class="fas fa-map-marker-alt mr-2"></i> Địa chỉ giao hàng:
                                        </span>
                                        {{ $item['shipping_address'] ?? 'Chưa có địa chỉ' }}
                                        <br>
                                    </p>
                                    <p class="text-gray-700 mt-2">
                                        <span class="font-semibold">
                                            <i class="fas fa-truck mr-2"></i> Phí vận chuyển:
                                        </span>
                                        {{ number_format($item['ship_charge']) }} VND
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-8 bg-gray-100 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-gray-800">
                            <i class="fas fa-calculator mr-2"></i> Tổng kết đơn hàng
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <p class="font-bold text-gray-600">Tạm tính:</p>
                            <p class="text-right">{{ number_format($sub_total) }} VND</p>
                            <p class="font-bold text-gray-600">Thuế:</p>
                            <p class="text-right">{{ number_format($tax) }} VND</p>
                            <p class="font-bold text-gray-600">Phí vận chuyển:</p>
                            <p class="text-right">{{ number_format($ship_charge) }} VND</p>
                            @if($discount_amount > 0)
                                <p class="font-bold text-gray-600">Giảm giá:</p>
                                <p class="text-right text-green-600">-{{ number_format($discount_amount) }} VND</p>
                            @endif
                            <p class="text-lg font-bold text-red-500">Tổng cộng:</p>
                            <p class="text-lg font-bold text-right text-red-500">{{ number_format($total) }} VND</p>
                        </div>
                    </div>


                    <button type="submit" 
                        class="w-full md:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-300 ease-in-out">
                        <i class="fas fa-check mr-2"></i> Xác nhận đặt hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('orderForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Ngăn chặn hành vi gửi form mặc định

        Swal.fire({
            title: 'Xác nhận',
            text: "Bạn có chắc chắn muốn đặt hàng không?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi form nếu người dùng xác nhận
                this.submit();
            }
        });
    });
</script>