<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Thanh toán đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('orders.place') }}">
                        @csrf
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-500">Thông tin người đặt hàng</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p><span class="font-semibold">Họ tên:</span> {{ $user->name }}</p>
                                    <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                                </div>
                                <div>
                                    <label for="phone_number" class="font-semibold">Số điện thoại:</label>
                                    <input type="text" name="phone_number" id="phone_number"
                                        class="border rounded px-2 py-1 w-full" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-500">Chi tiết đơn hàng</h3>
                            @foreach($cart as $productId => $item)
                                <div class="flex items-center justify-between border-b pb-4 mb-4">
                                    <div class="flex items-center">
                                        <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}"
                                            alt="{{ $item['name'] }}" class="w-20 h-20 object-cover mr-4">
                                        <div>
                                            <h4 class="font-semibold">{{ $item['name'] }}</h4>
                                            <p>Giá: {{ number_format($item['price']) }} VND</p>
                                            <p>Số lượng: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="address_{{ $productId }}" class="block mb-2">Địa chỉ giao hàng:</label>
                                        <textarea name="addresses[{{ $productId }}]" id="address_{{ $productId }}"
                                            class="border rounded px-2 py-1 w-full" required></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-500">Phương thức thanh toán</h3>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="1" class="form-radio" checked>
                                    <span class="ml-2">COD (Thanh toán khi nhận hàng)</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="2" class="form-radio">
                                    <span class="ml-2">PayPal</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-500">Mã giảm giá</h3>
                            <div class="flex items-center">
                                <input type="text" id="discount_code" name="discount_code"
                                    class="border rounded px-2 py-1 mr-2" placeholder="Nhập mã giảm giá (nếu có)">
                                <button type="button" id="applyDiscountBtn"
                                    class="bg-blue-500 text-white px-4 py-2 rounded">Áp dụng</button>
                            </div>
                            <p id="discount_message" class="mt-2 text-sm"></p>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-500">Tổng cộng</h3>
                            <p>Tạm tính: <span id="sub_total">{{ number_format($sub_total) }}</span> VND</p>
                            <p>Thuế (10%): <span id="tax">{{ number_format($tax) }}</span> VND</p>
                            <p>Phí vận chuyển: <span id="shipping">{{ number_format($ship_charge) }}</span> VND</p>
                            <p id="discount_row" class="hidden">Giảm giá: <span id="discount_amount"></span> VND</p>
                            <p class="font-semibold text-lg">Tổng: <span id="total">{{ number_format($total) }}</span>
                                VND</p>
                        </div>

                        <div class="mb-6">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Đặt hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#applyDiscountBtn').click(function () {
                var discountCode = $('#discount_code').val();

                console.log("Discount code:", discountCode); // Debugging

                $.ajax({
                    url: '{{ route("orders.check-discount") }}',
                    type: 'POST',
                    data: {
                        discount_code: discountCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log("Response:", response); // Debugging

                        if (response.success) {
                            $('#discount_message').text('Mã giảm giá hợp lệ!')
                                .removeClass('text-red-500')
                                .addClass('text-green-500');

                            $('#discount_row').removeClass('hidden');
                            var discountAmount = 0;
                            var subTotal = parseFloat($('#sub_total').text().replace(/,/g, ''));

                            if (response.discount.amount) {
                                discountAmount = response.discount.amount;
                            } else if (response.discount.percentage) {
                                discountAmount = subTotal * (response.discount.percentage / 100);
                            }

                            $('#discount_amount').text(numberFormat(discountAmount));

                            var tax = parseFloat($('#tax').text().replace(/,/g, ''));
                            var shipping = parseFloat($('#shipping').text().replace(/,/g, ''));
                            var newTotal = subTotal + tax + shipping - discountAmount;
                            $('#total').text(numberFormat(newTotal));
                        } else {
                            $('#discount_message').text('Mã giảm giá không hợp lệ!')
                                .removeClass('text-green-500')
                                .addClass('text-red-500');
                            $('#discount_row').addClass('hidden');

                            var subTotal = parseFloat($('#sub_total').text().replace(/,/g, ''));
                            var tax = parseFloat($('#tax').text().replace(/,/g, ''));
                            var shipping = parseFloat($('#shipping').text().replace(/,/g, ''));
                            $('#total').text(numberFormat(subTotal + tax + shipping));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', xhr, status, error); // Debugging
                        $('#discount_message').text('Có lỗi xảy ra khi kiểm tra mã giảm giá: ' + error)
                            .removeClass('text-green-500')
                            .addClass('text-red-500');
                    }
                });
            });

            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(number);
            }
        });
    </script>
</x-app-layout>