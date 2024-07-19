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
                            <table class="min-w-full w-full bg-white">
                                <thead class="bg-gray-200 text-gray-600">
                                    <tr>
                                        <th class="py-3 px-6 text-left">Hình ảnh</th>
                                        <th class="py-3 px-6 text-left">Sản phẩm</th>
                                        <th class="py-3 px-6 text-center">Giá</th>
                                        <th class="py-3 px-6 text-center">Số lượng</th>
                                        <th class="py-3 px-6 text-center">Tổng</th>
                                        <th class="py-3 px-6 text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @foreach($cart as $id => $details)
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
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
                                            <td class="py-3 px-6 text-center">
                                                {{ $details['quantity'] }}
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
                            </table>
                        </div>

                        <div class="flex justify-end mt-4">
                            <a href="{{ route('orders.checkout') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
                    if (data.message) {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect changes
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng');
                });
        });
    });
</script>
