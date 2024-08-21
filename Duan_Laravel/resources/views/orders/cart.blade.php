<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Giỏ hàng') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(count($cart) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-red w-full bg-white">
                                            <thead class="bg-gray-200 text-gray-600">
                                                <tr>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-left">Hình ảnh</th>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-left">Sản phẩm</th>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-center">Giá</th>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-center">Số lượng</th>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-center">Tổng</th>
                                                    <th class="py-3 px-6 bg-red-500 text-white text-center">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700">
                                                @foreach($cart as $id => $details)
                                                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-id="{{ $id }}">
                                                        <td class="py-3 px-6 text-left">
                                                            <div class="flex items-center">
                                                                <img src="{{ asset('storage/' . $details['image']) }}"
                                                                    class="w-20 h-20 object-cover rounded" alt="{{ $details['name'] }}">
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-6 text-left">
                                                            {{ $details['name'] }}
                                                        </td>
                                                        <td class="py-3 px-6 text-center">
                                                            {{ number_format($details['price']) }} VND
                                                        </td>
                                                        <td class="py-2 px-4 text-sm text-gray-500 text-center">
                                                            <div class="flex items-center justify-center">
                                                                <button
                                                                    class="quantity-change bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-1 px-2 rounded-l"
                                                                    data-action="decrease" data-id="{{ $id }}">-</button>
                                                                <input type="text"
                                                                    class="quantity-input border border-gray-300 px-2 py-1 rounded-none text-center w-12"
                                                                    value="{{ $details['quantity'] }}" min="1" data-id="{{ $id }}" disabled>
                                                                <button
                                                                    class="quantity-change bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-1 px-2 rounded-r"
                                                                    data-action="increase" data-id="{{ $id }}">+</button>
                                                            </div>
                                                        </td>
                                                        <td class="py-3 px-6 text-center">
                                                            {{ number_format($details['price'] * $details['quantity']) }} VND
                                                        </td>
                                                        <td class="py-3 px-6 text-center">
                                                            <button class="text-red-500 hover:text-red-700 remove-from-cart"
                                                                data-id="{{ $id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-gray-100 font-bold">
                                                    <td colspan="4" class="py-3 px-6 text-red-500 text-right">Tổng cộng:</td>
                                                    <td class="item-total py-3 px-6 text-red-500 text-center" id="cart-total">
                                                        {{ number_format(collect($cart)->sum(function ($item) {
                        return $item['price'] * $item['quantity']; })) }} VND
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="flex justify-end mt-4">
                                        <a href="{{ route('orders.checkout') }}"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Tiến hành thanh toán
                                        </a>
                                    </div>
                    @else
                        <div class="text-center text-gray-500 py-6">
                            <p>Giỏ hàng trống</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function updateQuantity(productId, newQuantity) {
            $.ajax({
                url: '/orders/update-quantity',
                method: 'POST',
                data: JSON.stringify({ product_id: productId, quantity: newQuantity }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        updateCartDisplay(data.cart, data.total);
                    } else {
                        alert(data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật số lượng');
                }
            });
        }

        function removeFromCart(productId) {
            fetch('/orders/remove-from-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data.cart, data.total);
                        document.querySelector(`tr[data-id="${productId}"]`).remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng');
                });
        }

        function updateCartDisplay(cart, total) {
            for (let id in cart) {
                let item = cart[id];
                let row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.querySelector('.quantity-input').value = item.quantity;
                    let totalCell = row.querySelector('td:nth-child(5)');
                    totalCell.textContent = numberFormat(item.price * item.quantity) + ' VND';
                }
            }
            document.getElementById('cart-total').textContent = numberFormat(total) + ' VND';
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }

        document.querySelectorAll('.quantity-change').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                const input = this.parentElement.querySelector('.quantity-input');
                let newQuantity = parseInt(input.value);

                if (this.getAttribute('data-action') === 'increase') {
                    newQuantity++;
                } else {
                    newQuantity = Math.max(1, newQuantity - 1);
                }

                updateQuantity(productId, newQuantity);
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function () {
                const productId = this.getAttribute('data-id');
                const newQuantity = Math.max(1, parseInt(this.value));
                updateQuantity(productId, newQuantity);
            });
        });

        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                removeFromCart(productId);
            });
        });
    });
</script>