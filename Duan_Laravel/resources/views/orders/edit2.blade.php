<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('Chỉnh sửa hóa đơn') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

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

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">User</label>
                        <input type="text" value="{{ $order->user->name }} ({{ $order->user->email }})"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            readonly>
                        <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ $order->phone_number }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            required>
                        @error('phone_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
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
                        @error('payment_method')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    
                    <div class="mb-4">
                        <label for="discount_code_id" class="block text-sm font-medium text-gray-700">Discount Code</label>
                        <select name="discount_code_id" id="discount_code_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">No Discount</option>
                            @foreach($discountCodes as $discountCode)
                                <option value="{{ $discountCode->id }}" 
                                        data-type="{{ $discountCode->amount ? 'amount' : 'percentage' }}"
                                        data-value="{{ $discountCode->amount ?? $discountCode->percentage }}"
                                        {{ $order->discount_code_id == $discountCode->id ? 'selected' : '' }}>
                                {{ $discountCode->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Products</h3>
                    <div id="products">
                        @foreach($order->orderDetails as $index => $detail)
                            <div class="product-item border-t border-gray-200 pt-4 mt-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select name="products[{{ $index }}][id]"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->product_id }}" {{ $detail->product_id == $product->product_id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->price }} VND)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('products.0.id')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="products[{{ $index }}][quantity]"
                                        value="{{ $detail->quantity }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required min="1">
                                    @error('products.*.quantity')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                    <select name="shipping_addresses[{{ $index }}][type]"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shipping-address-type"
                                        required>
                                        <option value="existing" {{ $detail->shippingAddress->id ? 'selected' : '' }}>
                                            Existing Address</option>
                                        <option value="new" {{ !$detail->shippingAddress->id ? 'selected' : '' }}>New
                                            Address</option>
                                    </select>
                                    @error('shipping_addresses.*.type')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="existing-address mb-4"
                                    style="{{ $detail->shippingAddress->id ? '' : 'display: none;' }}">
                                    <select name="shipping_addresses[{{ $index }}][address_id]"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm existing-address-select"
                                        {{ $detail->shippingAddress->id ? 'required' : '' }}>
                                        <option value="">Select an address</option>
                                        @foreach($order->user->addresses as $address)
                                            <option value="{{ $address->id }}" {{ $detail->shippingAddress->id == $address->id ? 'selected' : '' }}>
                                                {{ $address->address }}, {{ $address->ward }}, {{ $address->district }},
                                                {{ $address->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shipping_addresses.*.address_id')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="new-address" style="{{ !$detail->shippingAddress->id ? '' : 'display: none;' }}">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Address</label>
                                        <input type="text" name="shipping_addresses[{{ $index }}][address]"
                                            value="{{ $detail->shippingAddress->address }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('shipping_addresses.*.address')
                                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="shipping_addresses[{{ $index }}][city]"
                                            value="{{ $detail->shippingAddress->city }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('shipping_addresses.*.city')
                                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">District</label>
                                        <input type="text" name="shipping_addresses[{{ $index }}][district]"
                                            value="{{ $detail->shippingAddress->district }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('shipping_addresses.*.district')
                                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Ward</label>
                                        <input type="text" name="shipping_addresses[{{ $index }}][ward]"
                                            value="{{ $detail->shippingAddress->ward }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('shipping_addresses.*.ward')
                                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                                        <input type="text" name="shipping_addresses[{{ $index }}][phone_number]"
                                            value="{{ $detail->shippingAddress->phone_number }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                    <input type="number" name="shipping_addresses[{{ $index }}][ship_charge]"
                                        value="{{ $detail->shippingAddress->ship_charge }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    @error('shipping_addresses.*.ship_charge')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="button"
                                    class="remove-product mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    style="{{ count($order->orderDetails) === 1 ? 'display: none;' : '' }}"
                                    {{ count($order->orderDetails) === 1 ? 'disabled' : '' }}>
                                    Remove Product
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 my-6">
                        <h3 class="text-xl font-semibold mb-4 text-blue-700">Tổng cộng</h3>
                        <div class="space-y-2">
                            <p class="flex justify-between"><span>Tạm tính:</span> <span id="sub_total">0 VND</span></p>
                            <p class="flex justify-between"><span>Thuế (10%):</span> <span id="tax">0 VND</span></p>
                            <p class="flex justify-between"><span>Phí vận chuyển:</span> <span id="total_shipping">0 VND</span></p>
                            <p id="discount_row" class="flex justify-between"><span>Giảm giá:</span> <span id="discount_amount">0 VND</span></p>
                            <p class="flex justify-between font-semibold text-lg border-t pt-2">
                            <span>Tổng:</span> <span id="total">0 VND</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" id="add-product"
                            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add Product
                        </button>

                        <div class="mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const users = @json($users);
    const products = @json($products);
    let shippingAddressCount = @json($order->orderDetails->count());

    const userNameInput = document.getElementById('user_name');
    const userEmailInput = document.getElementById('user_email');

    // Khởi tạo các địa chỉ giao hàng hiện có
    initializeExistingAddresses();

    // Xử lý thêm địa chỉ giao hàng mới
    document.getElementById('add_shipping_address').addEventListener('click', function() {
        addNewShippingAddress();
    });

    // Xử lý sự kiện cho các nút thêm sản phẩm
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-product')) {
            const index = e.target.closest('.shipping-address').dataset.index;
            addProduct(index);
        }
    });

    // Xử lý sự kiện cho các nút xóa sản phẩm
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            const row = e.target.closest('tr');
            const productList = row.closest('tbody');
            row.remove();
            updateTotals();
        }
    });

    // Xử lý thay đổi loại địa chỉ (hiện có / mới)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('new-address-checkbox')) {
            const addressDiv = e.target.closest('.shipping-address');
            const isNewAddress = e.target.checked;
            toggleAddressFields(addressDiv, isNewAddress);
        }
    });

    // Xử lý thay đổi địa chỉ hiện có
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('address-select')) {
            const addressDiv = e.target.closest('.shipping-address');
            const addressId = e.target.value;
            populateAddressFields(addressDiv, addressId);
        }
    });

    // Cập nhật tổng tiền khi có thay đổi số lượng hoặc phí vận chuyển
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="number"]') || e.target.matches('input[name$="[ship_charge]"]')) {
            updateTotals();
        }
    });

    // Xử lý thay đổi mã giảm giá
    document.getElementById('discount_code_id').addEventListener('change', updateTotals);

    function initializeExistingAddresses() {
        document.querySelectorAll('.shipping-address').forEach((addressDiv, index) => {
            const productList = addressDiv.querySelector(`#product_list_${index}`);
            const existingProducts = @json($order->orderDetails[$index]->products);
            
            existingProducts.forEach(product => {
                addProductToList(productList, product, index);
            });

            const newAddressCheckbox = addressDiv.querySelector('.new-address-checkbox');
            toggleAddressFields(addressDiv, newAddressCheckbox.checked);
        });

        updateTotals();
    }

    function addNewShippingAddress() {
        const shippingAddressesDiv = document.getElementById('shipping_addresses');
        const newShippingAddressDiv = document.querySelector('.shipping-address').cloneNode(true);

        newShippingAddressDiv.dataset.index = shippingAddressCount;

        // Reset all input fields
        newShippingAddressDiv.querySelectorAll('input, select').forEach(input => {
            input.id = input.id.replace(/_\d+/, `_${shippingAddressCount}`);
            input.name = input.name.replace(/\[\d+\]/, `[${shippingAddressCount}]`);
            input.value = '';
        });

        // Clear the product list
        const productList = newShippingAddressDiv.querySelector('tbody');
        productList.id = `product_list_${shippingAddressCount}`;
        productList.innerHTML = '';

        // Update the product select ID
        const productSelect = newShippingAddressDiv.querySelector('.product-select');
        productSelect.id = `product_select_${shippingAddressCount}`;

        // Update the add product button
        const addProductButton = newShippingAddressDiv.querySelector('.add-product');
        addProductButton.dataset.index = shippingAddressCount;

        // Reset and update the new address checkbox
        const newAddressCheckbox = newShippingAddressDiv.querySelector('.new-address-checkbox');
        newAddressCheckbox.checked = true;
        toggleAddressFields(newShippingAddressDiv, true);

        shippingAddressesDiv.appendChild(newShippingAddressDiv);

        shippingAddressCount++;
    }

    function addProduct(index) {
        const productSelect = document.getElementById(`product_select_${index}`);
        const productList = document.getElementById(`product_list_${index}`);
        const selectedOption = productSelect.options[productSelect.selectedIndex];

        if (selectedOption.value) {
            const product = {
                id: selectedOption.value,
                name: selectedOption.dataset.name,
                price: selectedOption.dataset.price,
                image: selectedOption.dataset.image
            };

            addProductToList(productList, product, index);
            updateTotals();
        }
    }

    function addProductToList(productList, product, index) {
        // Check if the product already exists
        const existingRow = productList.querySelector(`tr[data-product-id="${product.id}"]`);

        if (existingRow) {
            // If the product exists, increment the quantity
            const quantityInput = existingRow.querySelector('.quantity-input');
            let quantity = parseInt(quantityInput.value);
            if (quantity < 20) {
                quantityInput.value = quantity + 1;
            }
        } else {
            // If the product doesn't exist, create a new row
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-100');
            row.dataset.productId = product.id;

            row.innerHTML = `
                <td class="px-6 py-4 text-center whitespace-nowrap">
                    <img src="{{ asset('storage/') }}/${product.image}" alt="${product.name}" class="w-12 h-12">
                </td>
                <td class="px-6 py-4 text-center whitespace-nowrap">${product.name}</td>
                <td class="px-6 py-4 text-center whitespace-nowrap">${product.id}</td>
                <td class="px-6 py-4 text-center whitespace-nowrap">${numberFormat(product.price)} VND</td>
                <td class="px-6 py-4 text-center whitespace-nowrap">
                    <div class="flex items-center justify-center">
                        <button type="button" class="decrement-quantity px-3 py-1 bg-gray-300 rounded-l">-</button>
                        <input type="number" name="shipping_addresses[${index}][products][${product.id}][quantity]" value="1" min="1" max="20" class="quantity-input mt-1 w-20 shadow-sm border rounded-md text-center" readonly>
                        <button type="button" class="increment-quantity px-3 py-1 bg-gray-300 rounded-r">+</button>
                        <input type="hidden" name="shipping_addresses[${index}][products][${product.id}][product_id]" value="${product.id}">
                    </div>
                </td>
                <td class="px-6 py-4 text-center whitespace-nowrap">
                    <button type="button" class="remove-product text-white px-6 py-2 bg-red-500 rounded">Xóa</button>
                </td>
            `;

            productList.appendChild(row);

            // Add event listeners for quantity buttons
            row.querySelector('.decrement-quantity').addEventListener('click', function() {
                let quantityInput = row.querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                }
                updateTotals();
            });

            row.querySelector('.increment-quantity').addEventListener('click', function() {
                let quantityInput = row.querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);
                if (quantity < 20) {
                    quantityInput.value = quantity + 1;
                }
                updateTotals();
            });
        }
    }

    function toggleAddressFields(addressDiv, isNewAddress) {
        const existingAddressSelect = addressDiv.querySelector('.address-select');
        const newAddressFields = addressDiv.querySelector('.new-address');

        if (isNewAddress) {
            existingAddressSelect.disabled = true;
            existingAddressSelect.value = '';
            newAddressFields.style.display = 'block';
            newAddressFields.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        } else {
            existingAddressSelect.disabled = false;
            newAddressFields.style.display = 'none';
            newAddressFields.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });
        }
    }

    function populateAddressFields(addressDiv, addressId) {
        const address = @json($order->user->addresses)->find(a => a.id == addressId);
        if (address) {
            addressDiv.querySelector('input[name$="[address]"]').value = address.address;
            addressDiv.querySelector('input[name$="[city]"]').value = address.city;
            addressDiv.querySelector('input[name$="[district]"]').value = address.district;
            addressDiv.querySelector('input[name$="[ward]"]').value = address.ward;
            addressDiv.querySelector('input[name$="[phone_number]"]').value = address.phone_number;
        }
    }

    function updateTotals() {
        let subTotal = 0;
        let totalShipping = 0;
        let discount = 0;
        const taxRate = 0.10;

        document.querySelectorAll('.shipping-address').forEach(addressDiv => {
            const productList = addressDiv.querySelector('tbody');
            const rows = productList.querySelectorAll('tr');

            rows.forEach(row => {
                const priceCell = row.querySelector('td:nth-child(4)');
                if (priceCell) {
                    const price = parseFloat(priceCell.textContent.replace(/[^\d]/g, ''));
                    const quantityInput = row.querySelector('input[type="number"]');
                    if (quantityInput) {
                        const quantity = parseInt(quantityInput.value);
                        subTotal += price * quantity;
                    }
                }
            });

            const shipChargeInput = addressDiv.querySelector('input[name$="[ship_charge]"]');
            totalShipping += parseFloat(shipChargeInput.value || 0);
        });

        // Apply discount code
        const discountSelect = document.getElementById('discount_code_id');
        if (discountSelect.value) {
            const selectedOption = discountSelect.options[discountSelect.selectedIndex];
            const discountType = selectedOption.dataset.type;
            const discountValue = parseFloat(selectedOption.dataset.value);

            if (discountType === 'percentage') {
                discount = subTotal * (discountValue / 100);
            } else if (discountType === 'amount') {
                discount = discountValue;
            }
        }

        const tax = subTotal * taxRate;
        const total = subTotal + tax + totalShipping - discount;

        document.getElementById('sub_total').textContent = numberFormat(subTotal) + ' VND';
        document.getElementById('tax').textContent = numberFormat(tax) + ' VND';
        document.getElementById('total_shipping').textContent = numberFormat(totalShipping) + ' VND';
        document.getElementById('discount_amount').textContent = numberFormat(discount) + ' VND';
        document.getElementById('total').textContent = numberFormat(total) + ' VND';
    }

    function numberFormat(number) {
        return new Intl.NumberFormat('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(Math.round(number));
    }
});
</script>
