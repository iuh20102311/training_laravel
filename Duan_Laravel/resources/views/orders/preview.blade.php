<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Xác nhận đơn hàng') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-2 px-4 py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="order-info-section p-6 bg-white shadow-md rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-blue-500">Thông tin đơn hàng</h3>
                <div class="space-y-3">
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Khách hàng:</span>
                        <span>{{ $user->name }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Email:</span>
                        <span>{{ $user->email }}</span>
                    </p>
                    
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Ngày đặt hàng:</span>
                        <span>{{ now()->format('d/m/Y H:i:s') }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-semibold text-gray-600">Phương thức thanh toán:</span>
                        <span>{{ $payment_method == 1 ? 'COD' : 'PayPal' }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="product-details-section mt-6">
            @foreach($cart as $product_id => $item)
                <div class="p-6 bg-white shadow-md rounded-lg mb-4">
                    <div class="flex items-center">
                        <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}"
                            alt="{{ $item['name'] }}" class="w-48 h-48 object-cover mr-6">
                        <div class="flex-grow">
                            <h4 class="text-lg font-semibold mb-2 text-blue-500">{{ $item['name'] }}</h4>
                            <p class="text-gray-600">Giá: {{ number_format($item['price']) }} VND</p>
                            <p class="text-gray-600">Số lượng: {{ $item['quantity'] }}</p>
                            <p class="text-gray-600">Tổng: {{ number_format($item['price'] * $item['quantity']) }} VND</p>
                        </div>
                        <div class="ml-6">
                            <h5 class="text-md font-semibold mb-2 text-blue-500">Địa chỉ giao hàng:</h5>
                            <p>{{ $addresses[$product_id] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="summary-section p-6 bg-white shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-4 text-blue-500">Tổng kết</h3>
            <div class="space-y-2">
                <p class="flex justify-between">
                    <span class="font-semibold text-gray-600">Tổng tiền hàng:</span>
                    <span id="sub_total">{{ number_format($sub_total) }} VND</span>
                </p>
                <p class="flex justify-between">
                    <span class="font-semibold text-gray-600">Thuế (10%):</span>
                    <span id="tax">{{ number_format($tax) }} VND</span>
                </p>
                <p class="flex justify-between">
                    <span class="font-semibold text-gray-600">Phí vận chuyển:</span>
                    <span id="ship_charge">{{ number_format($ship_charge) }} VND</span>
                </p>
                <p class="flex justify-between text-green-600" id="discount_amount_container" style="display: none;">
                    <span class="font-semibold">Giảm giá:</span>
                    <span id="discount_amount">0 VND</span>
                </p>
                <p class="flex justify-between font-bold text-lg text-red-600">
                    <span>Tổng cộng:</span>
                    <span id="total">{{ number_format($total) }} VND</span>
                </p>
            </div>
        </div>
    </div>

    <div class="mt-6 mb-6 text-center">
        <form method="POST" action="{{ route('orders.place') }}">
            @csrf
            <input type="hidden" name="phone_number" value="{{ $phone_number }}">
            <input type="hidden" name="payment_method" value="{{ $payment_method }}">
            <input type="hidden" name="discount_code" id="hidden_discount_code" value="">
            @foreach($addresses as $product_id => $address)
                <input type="hidden" name="addresses[{{ $product_id }}]" value="{{ $address }}">
            @endforeach
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-4 rounded">
                Xác nhận đặt hàng
            </button>
        </form>
        <a href="{{ route('cart.show') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-4 px-4 rounded mt-4 inline-block">
            Quay lại giỏ hàng
        </a>
    </div>
</x-app-layout>

<script>
document.getElementById('apply_discount').addEventListener('click', function() {
    const discountCode = document.getElementById('discount_code').value;
    fetch('{{ route("check.discount") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ discount_code: discountCode })
    })
    .then(response => response.json())
    .then(data => {
        const messageElement = document.getElementById('discount_message');
        if (data.success) {
            messageElement.textContent = data.message;
            messageElement.classList.remove('text-red-500');
            messageElement.classList.add('text-green-500');
            updateTotalWithDiscount(data.discount);
            document.getElementById('hidden_discount_code').value = discountCode;
        } else {
            messageElement.textContent = data.message;
            messageElement.classList.remove('text-green-500');
            messageElement.classList.add('text-red-500');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const messageElement = document.getElementById('discount_message');
        messageElement.textContent = 'Có lỗi xảy ra khi áp dụng mã giảm giá';
        messageElement.classList.remove('text-green-500');
        messageElement.classList.add('text-red-500');
    });
});

function updateTotalWithDiscount(discount) {
    const subTotalElement = document.getElementById('sub_total');
    const taxElement = document.getElementById('tax');
    const shipChargeElement = document.getElementById('ship_charge');
    const totalElement = document.getElementById('total');
    const discountAmountElement = document.getElementById('discount_amount');
    const discountAmountContainer = document.getElementById('discount_amount_container');

    const subTotal = parseFloat(subTotalElement.textContent.replace(/[^0-9.-]+/g,""));
    const tax = parseFloat(taxElement.textContent.replace(/[^0-9.-]+/g,""));
    const shipCharge = parseFloat(shipChargeElement.textContent.replace(/[^0-9.-]+/g,""));
    const discountAmount = parseFloat(discount);

    const total = subTotal + tax + shipCharge - discountAmount;

    discountAmountElement.textContent = discountAmount.toLocaleString() + ' VND';
    totalElement.textContent = total.toLocaleString() + ' VND';

    if (discountAmount > 0) {
        discountAmountContainer.style.display = 'flex';
    } else {
        discountAmountContainer.style.display = 'none';
    }
}
</script>
