<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight text-center">
            {{ __('Thanh toán đơn hàng') }}
        </h2>
    </x-slot>


    <div class="py-6 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('orders.preview') }}">
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
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Cột bên trái -->
                            <div class="md:col-span-2 space-y-8">
                                <!-- Thông tin người đặt hàng -->
                                <div class="bg-blue-50 p-6 rounded-lg shadow-lg border border-blue-100">
                                    <h3 class="text-xl font-semibold mb-4 text-blue-700">Thông tin người đặt hàng</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="mb-2"><span class="font-semibold">Họ tên:</span> {{ $user->name }}
                                            </p>
                                            <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                                        </div>
                                        <div>
                                            <label for="phone_number" class="font-semibold block mb-2">Số điện
                                                thoại:</label>
                                            <input type="text" name="phone_number" id="phone_number"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chi tiết đơn hàng -->
                                <div>
                                    <h3 class="py-6 text-xl text-center font-semibold text-blue-700">Chi tiết đơn hàng
                                    </h3>
                                    @foreach($cart as $productId => $item)
                                        <div class="product-item bg-white p-6 rounded-lg shadow-lg mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between border border-gray-200">
                                            <div class="flex items-center mb-4 sm:mb-0">
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['name'] }}"
                                                    class="w-20 h-20 object-cover rounded-md mr-4 border border-gray-300">
                                                <div>
                                                    <h4 class="font-semibold text-base">{{ $item['name'] }}</h4>
                                                    <p class="text-gray-600 text-sm">Giá:
                                                        {{ number_format($item['price']) }} VND
                                                    </p>
                                                    <p class="text-gray-600 text-sm">Số lượng: {{ $item['quantity'] }}</p>
                                                    <p id="ship_charge_{{ $productId }}"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md ship-charge"
                                                        data-index="{{ $loop->index }}">Phí vận chuyển:
                                                        {{ number_format($item['ship_charge']) }} VND
                                                    </p>
                                                    <input type="hidden"
                                                        name="shipping_addresses[{{ $productId }}][ship_charge]"
                                                        value="10000">
                                                </div>
                                            </div>
                                            <!-- Thay thế phần địa chỉ giao hàng trong vòng lặp sản phẩm -->
                                            <div class="w-full sm:w-1/2">
                                                <label for="address_type_{{ $productId }}"
                                                    class="block mb-2 font-semibold">Địa chỉ giao hàng:</label>
                                                <select name="shipping_addresses[{{ $productId }}][type]"
                                                    id="address_type_{{ $productId }}"
                                                    class="shipping-address-type mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    required>
                                                    <option value="existing">Địa chỉ hiện có</option>
                                                    <option value="new">Địa chỉ mới</option>
                                                </select>

                                                <div class="existing-address mt-2">
                                                    <select name="shipping_addresses[{{ $productId }}][address_id]"
                                                        class="existing-address-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        required>
                                                        <option value="">Chọn địa chỉ</option>
                                                        @foreach($user->addresses as $address)
                                                            <option value="{{ $address->id }}">
                                                                {{ $address->address }}, {{ $address->ward }},
                                                                {{ $address->district }}, {{ $address->city }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="new-address mt-2" style="display: none;">
                                                    <input type="text" name="shipping_addresses[{{ $productId }}][address]"
                                                        placeholder="Địa chỉ"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                    <input type="text" name="shipping_addresses[{{ $productId }}][city]"
                                                        placeholder="Thành phố"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                    <input type="text" name="shipping_addresses[{{ $productId }}][district]"
                                                        placeholder="Quận/Huyện"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                    <input type="text" name="shipping_addresses[{{ $productId }}][ward]"
                                                        placeholder="Phường/Xã"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                    <input type="text"
                                                        name="shipping_addresses[{{ $productId }}][phone_number]"
                                                        placeholder="Số điện thoại"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Cột bên phải -->
                            <div class="space-y-8 py-4">
                                <!-- Phương thức thanh toán -->
                                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 py-4">
                                    <h3 class="text-xl font-semibold mb-4 text-blue-700">Phương thức thanh toán</h3>
                                    <div class="space-y-4">
                                        <label
                                            class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-300">
                                            <input type="radio" name="payment_method" value="1"
                                                class="form-radio text-blue-600" checked>
                                            <span class="ml-2">COD (Thanh toán khi nhận hàng)</span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-300">
                                            <input type="radio" name="payment_method" value="2"
                                                class="form-radio text-blue-600">
                                            <span class="ml-2">PayPal</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mã giảm giá -->
                                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 my-6">
                                    <h3 class="text-xl font-semibold mb-4 text-blue-700">Mã giảm giá</h3>
                                    <div class="flex items-center">
                                        <input type="text" id="discount_code" name="discount_code"
                                            class="flex-grow px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Nhập mã giảm giá">
                                        <button type="button" id="applyDiscountBtn"
                                            class="bg-red-600 text-white px-4 py-2 rounded-r-md hover:bg-red-700 transition duration-300">Áp
                                            dụng</button>
                                    </div>
                                    <p id="discount_message" class="mt-2 text-sm text-green-600"></p>
                                </div>

                                <!-- Tổng cộng -->
                                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 my-6">
                                    <h3 class="text-xl font-semibold mb-4 text-blue-700">Tổng cộng</h3>
                                    <div class="space-y-2">
                                        <p class="flex justify-between"><span>Tạm tính:</span> <span
                                                id="sub_total">{{ number_format($sub_total) }} VND</span></p>
                                        <p class="flex justify-between"><span>Thuế (10%):</span> <span
                                                id="tax">{{ number_format($tax) }} VND</span></p>
                                        <p class="flex justify-between"><span>Phí vận chuyển:</span> <span
                                                id="total_shipping">0 VND</span></p>
                                        <p id="discount_row" class="flex justify-between"><span>Giảm giá:</span> <span
                                                id="discount_amount">0 VND</span></p>
                                        <p class="flex justify-between font-semibold text-lg border-t pt-2">
                                            <span>Tổng:</span> <span id="total">{{ number_format($total) }} VND</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Nút đặt hàng -->
                                <button type="submit"
                                    class="w-full bg-green-600 text-black px-6 py-6 rounded-lg text-lg font-semibold hover:bg-green-700 transition duration-300">
                                    Xem lại đơn hàng
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.shipping-address-type').forEach(function (element) {
            element.addEventListener('change', function () {
                var selectedType = this.value;
                var productItem = this.closest('.product-item');
                var newAddressFields = productItem.querySelector('.new-address');
                var existingAddressFields = productItem.querySelector('.existing-address');

                if (selectedType === 'new') {
                    newAddressFields.style.display = 'block';
                    existingAddressFields.style.display = 'none';
                } else {
                    newAddressFields.style.display = 'none';
                    existingAddressFields.style.display = 'block';
                }
            });
        });
    });

    $(document).ready(function () {
        $('#applyDiscountBtn').click(function () {
            var discountCode = $('#discount_code').val();

            $.ajax({
                url: '{{ route("orders.check-discount") }}',
                type: 'POST',
                data: {
                    discount_code: discountCode,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        $('#discount_message').text('Mã giảm giá hợp lệ!')
                            .removeClass('text-red-500')
                            .addClass('text-green-500');

                        var discountAmount = 0;
                        var subTotal = parseFloat($('#sub_total').text().replace(/[^0-9.-]+/g, ''));

                        if (response.discount.amount) {
                            discountAmount = response.discount.amount;
                        } else if (response.discount.percentage) {
                            discountAmount = subTotal * (response.discount.percentage / 100);
                        }

                        $('#discount_amount').text(numberFormat(Math.round(discountAmount)) + ' VND');

                        updateTotal();
                    } else {
                        $('#discount_message').text('Mã giảm giá không hợp lệ!')
                            .removeClass('text-green-500')
                            .addClass('text-red-500');
                        $('#discount_amount').text('0 VND');
                        updateTotal();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    $('#discount_message').text('Có lỗi xảy ra khi kiểm tra mã giảm giá: ' + error)
                        .removeClass('text-green-500')
                        .addClass('text-red-500');
                }
            });
        });

        function numberFormat(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }

        function calculateShippingCharge(index) {
            const baseCharge = 10000;
            return baseCharge * Math.pow(1.1, index);
        }

        function updateShippingCharges() {
            let totalShipping = 0;
            $('.ship-charge').each(function (index) {
                const charge = calculateShippingCharge(index);
                $(this).val(Math.round(charge));
                totalShipping += charge;
            });
            return totalShipping;
        }

        function updateTotal() {
            let subTotal = parseFloat($('#sub_total').text().replace(/[^0-9.-]+/g, ""));
            let tax = parseFloat($('#tax').text().replace(/[^0-9.-]+/g, ""));
            let discountAmount = parseFloat($('#discount_amount').text().replace(/[^0-9.-]+/g, ""));
            let totalShipping = updateShippingCharges();

            $('#total_shipping').text(numberFormat(totalShipping) + ' VND');

            let total = subTotal + tax + totalShipping - discountAmount;
            $('#total').text(numberFormat(Math.round(total)) + ' VND');
            $('#total_amount').val(Math.round(total));
        } updateTotal();
    });
</script>