<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('Cập nhật hóa đơn') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('orders.update', $order->order_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                        <select name="user_id" id="user_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            value="{{ $order->phone_number }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                            Method</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="1" {{ $order->payment_method == 1 ? 'selected' : '' }}>COD</option>
                            <option value="2" {{ $order->payment_method == 2 ? 'selected' : '' }}>PayPal</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="order_status" class="block text-sm font-medium text-gray-700">Order Status</label>
                        <select name="order_status" id="order_status"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="discount_code_id" class="block text-sm font-medium text-gray-700">Discount
                            Code</label>
                        <select name="discount_code_id" id="discount_code_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">No Discount</option>
                            @foreach($discountCodes as $discountCode)
                                <option value="{{ $discountCode->id }}" {{ $order->discount_code_id == $discountCode->id ? 'selected' : '' }}>
                                    {{ $discountCode->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Products</h3>
                    <div id="products">
                        @foreach($order->orderDetails as $index => $orderDetail)
                            <div class="product-item border-t border-gray-200 pt-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="products[{{ $index }}][product_id]"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->product_id }}" {{ $orderDetail->product_id == $product->product_id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ number_format($product->price) }} VND)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="products[{{ $index }}][quantity]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->quantity }}" required min="1">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                    <input type="text" name="shipping_addresses[{{ $index }}][address]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->address }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="shipping_addresses[{{ $index }}][city]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->city }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="shipping_addresses[{{ $index }}][district]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->district }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Ward</label>
                                    <input type="text" name="shipping_addresses[{{ $index }}][ward]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->ward }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                                    <input type="text" name="shipping_addresses[{{ $index }}][phone_number]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->phone_number }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                    <input type="number" name="shipping_addresses[{{ $index }}][ship_charge]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        value="{{ $orderDetail->shippingAddress->ship_charge }}" required>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-product"
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Product </button>

                    <div class="mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Update Order </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let productCount = {{ count($order->orderDetails) }};

        document.getElementById('add-product').addEventListener('click', function () {
            const productHtml = `
                <div class="product-item border-t border-gray-200 pt-4 mt-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="products[${productCount}][product_id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->name }} ({{ number_format($product->price) }} VND)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="products[${productCount}][quantity]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required min="1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                        <input type="text" name="shipping_addresses[${productCount}][address]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="shipping_addresses[${productCount}][city]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">District</label>
                        <input type="text" name="shipping_addresses[${productCount}][district]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Ward</label>
                        <input type="text" name="shipping_addresses[${productCount}][ward]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                        <input type="text" name="shipping_addresses[${productCount}][phone_number]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                        <input type="number" name="shipping_addresses[${productCount}][ship_charge]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                </div>
            `;

            document.getElementById('products').insertAdjacentHTML('beforeend', productHtml);
            productCount++;
        });
    </script>
</x-app-layout>