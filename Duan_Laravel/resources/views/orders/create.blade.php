<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('Thêm hóa đơn mới') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('orders.store') }}" method="POST">
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

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin khách hàng</h3>
                    <div class="mb-4 border-t pt-4">
                        <label for="user_type" class="block text-sm font-medium text-gray-700">Loại khách hàng muốn tạo
                            đơn hàng (User Type)</label>
                        <select name="user_type" id="user_type"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="existing">Khách hàng đã đăng kí (Existing User)</option>
                            <option value="new">Khách hàng mới (New User)</option>
                        </select>
                        @error('user_type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="existing_user_fields">
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Khách hàng</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">Select an user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="new_user_fields" style="display: none;">
                        <div class="mb-4">
                            <label for="new_user_name" class="block text-sm font-medium text-gray-700">Tên khách hàng
                                mới (Name)</label>
                            <input type="text" name="new_user_name" id="new_user_name"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('new_user_name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="new_user_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="new_user_email" id="new_user_email"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('new_user_email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Số điện thoại (Phone
                            Number)</label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            required>
                        @error('phone_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Hình thức thanh toán
                            (Payment
                            Method)</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="1">COD</option>
                            <option value="2">PayPal</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="discount_code_id" class="block text-sm font-medium text-gray-700">Discount
                            Code</label>
                        <select name="discount_code_id" id="discount_code_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">No Discount</option>
                            @foreach($discountCodes as $discountCode)
                                <option value="{{ $discountCode['id'] }}" data-type="{{ $discountCode['type'] }}"
                                    data-value="{{ $discountCode['value'] }}">
                                    {{ $discountCode['code'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin sản phẩm</h3>
                    <div id="products">
                        <div class="product-item border-t border-gray-200 pt-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Sản phẩm</label>
                                <select name="products[0][id]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name }}
                                            ({{ number_format($product->price) }} VND)</option>
                                    @endforeach
                                </select>
                                @error('products.0.id')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Số lượng (Quantity)</label>
                                <input type="number" name="products[0][quantity]"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required min="1">
                                @error('products.*.quantity')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng (Shipping
                                    Address)</label>
                                <select name="shipping_addresses[0][type]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shipping-address-type"
                                    required>
                                    <option value="existing">Địa chỉ có sẵn (Existing Address)</option>
                                    <option value="new">Địa chỉ mới (New Address)</option>
                                </select>
                                @error('shipping_addresses.*.type')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="existing-address mb-4">
                                <select name="shipping_addresses[0][address_id]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm existing-address-select"
                                    required>
                                    <option value="">Select an address</option>
                                </select>
                                @error('shipping_addresses.*.address_id')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="new-address" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" name="shipping_addresses[0][address]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('shipping_addresses.*.address')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="shipping_addresses[0][city]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('shipping_addresses.*.city')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="shipping_addresses[0][district]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('shipping_addresses.*.district')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Ward</label>
                                    <input type="text" name="shipping_addresses[0][ward]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('shipping_addresses.*.ward')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                                    <input type="text" name="shipping_addresses[0][phone_number]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('shipping_addresses.*.phone_number')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                <input type="number" name="shipping_addresses[0][ship_charge]"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required min="0">
                                @error('shipping_addresses.*.ship_charge')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="button"
                                class="remove-product mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                style="display: none;" disabled>
                                Remove Product
                            </button>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 my-6">
                        <h3 class="text-xl font-semibold mb-4 text-blue-700">Tổng cộng</h3>
                        <div class="space-y-2">
                            <p class="flex justify-between"><span>Tạm tính:</span> <span id="sub_total">0 VND</span></p>
                            <p class="flex justify-between"><span>Thuế (10%):</span> <span id="tax">0 VND</span></p>
                            <p class="flex justify-between"><span>Phí vận chuyển:</span> <span id="total_shipping">0
                                    VND</span></p>
                            <p id="discount_row" class="flex justify-between"><span>Giảm giá:</span> <span
                                    id="discount_amount">0 VND</span></p>
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
                                Create Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let productCount = 1;
    const users = @json($users);
    let productPrices = @json($products->pluck('price', 'product_id'));

    document.getElementById('add-product').addEventListener('click', function () {
        const productHtml = `
                <div class="product-item border-t border-gray-200 pt-4 mt-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sản phẩm (Product)</label>
                        <select name="products[${productCount}][id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->name }} ({{ number_format($product->price) }} VND)</option>
                            @endforeach
                        </select>
                        @error('products.0.id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Số lượng (Quantity)</label>
                        <input type="number" name="products[${productCount}][quantity]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required min="1">
                        @error('products.*.quantity')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror    
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng (Shipping Address)</label>
                        <select name="shipping_addresses[${productCount}][type]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shipping-address-type" required>
                            <option value="existing">Địa chỉ có sẵn (Existing Address)</option>
                            <option value="new">Địa chỉ mới (New Address)</option>
                        </select>
                        @error('shipping_addresses.*.type')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="existing-address mb-4">
                        <select name="shipping_addresses[${productCount}][address_id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm existing-address-select" required>
                            <option value="">Select an address</option>
                        </select>
                        @error('shipping_addresses.*.address_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="new-address" style="display: none;">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="shipping_addresses[${productCount}][address]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_addresses.*.address')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror        
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="shipping_addresses[${productCount}][city]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_addresses.*.city')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">District</label>
                            <input type="text" name="shipping_addresses[${productCount}][district]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_addresses.*.district')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Ward</label>
                            <input type="text" name="shipping_addresses[${productCount}][ward]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_addresses.*.ward')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                            <input type="text" name="shipping_addresses[${productCount}][phone_number]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('shipping_addresses.*.phone_number')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                        <input type="number" name="shipping_addresses[${productCount}][ship_charge]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required min="0">
                        @error('shipping_addresses.*.ship_charge')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="button" class="remove-product mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Remove Product
                    </button>
                </div>
            `;
        document.getElementById('products').insertAdjacentHTML('beforeend', productHtml);
        productCount++;
        initializeAddressSelects();
        updateRemoveButtons();
        updateTotals();
        addEventListeners();
    });

    function initializeAddressSelects() {
        document.querySelectorAll('.shipping-address-type').forEach(select => {
            select.addEventListener('change', function () {
                const productItem = this.closest('.product-item');
                const existingAddress = productItem.querySelector('.existing-address');
                const newAddress = productItem.querySelector('.new-address');
                if (this.value === 'existing') {
                    existingAddress.style.display = 'block';
                    newAddress.style.display = 'none';
                    existingAddress.querySelectorAll('select, input').forEach(el => el.required = true);
                    newAddress.querySelectorAll('input').forEach(el => el.required = false);
                } else {
                    existingAddress.style.display = 'none';
                    newAddress.style.display = 'block';
                    existingAddress.querySelectorAll('select, input').forEach(el => el.required = false);
                    newAddress.querySelectorAll('input').forEach(el => el.required = true);
                }
            });
        });

        updateAddressOptions();
    }

    document.getElementById('user_type').addEventListener('change', function () {
        const existingUserFields = document.getElementById('existing_user_fields');
        const newUserFields = document.getElementById('new_user_fields');
        if (this.value === 'existing') {
            existingUserFields.style.display = 'block';
            newUserFields.style.display = 'none';
        } else {
            existingUserFields.style.display = 'none';
            newUserFields.style.display = 'block';
        }
    });

    function updateAddressOptions() {
        const userType = document.getElementById('user_type').value;
        const userId = document.getElementById('user_id').value;

        if (userType === 'existing') {
            const user = users.find(u => u.id == userId);
            const addressOptions = user.addresses.map(address =>
                `<option value="${address.id}">${address.address}, ${address.ward}, ${address.district}, ${address.city}</option>`
            ).join('');

            document.querySelectorAll('.existing-address-select').forEach(select => {
                select.innerHTML = `<option value="">Select an address</option>${addressOptions}`;
            });
        } else {
            document.querySelectorAll('.existing-address-select').forEach(select => {
                select.innerHTML = `<option value="">No existing addresses</option>`;
            });
        }
    }

    // Xử lý sự kiện xóa sản phẩm
    document.getElementById('products').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            if (productCount > 1) {
                e.target.closest('.product-item').remove();
                productCount--;
                updateRemoveButtons();
                updateTotals(); // Cập nhật tổng tiền sau khi xóa sản phẩm
            }
        }
    });

    // Hàm để cập nhật trạng thái của các nút xóa
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-product');
        removeButtons.forEach(button => {
            button.style.display = productCount === 1 ? 'none' : 'inline-flex';
            button.disabled = productCount === 1;
        });
    }

    function updateTotals() {
        let subTotal = 0;
        let totalShipping = 0;

        document.querySelectorAll('.product-item').forEach(item => {
            const productSelect = item.querySelector('select[name^="products"][name$="[id]"]');
            const productId = productSelect.value;
            const quantity = parseInt(item.querySelector('input[name^="products"][name$="[quantity]"]').value) || 0;
            const shippingCharge = parseFloat(item.querySelector('input[name^="shipping_addresses"][name$="[ship_charge]"]').value) || 0;

            console.log('Product ID:', productId, 'Quantity:', quantity, 'Price:', productPrices[productId]);

            if (productId && productPrices[productId]) {
                subTotal += productPrices[productId] * quantity;
            }
            totalShipping += shippingCharge;
        });

        const tax = subTotal * 0.1;
        let discountAmount = 0;

        const discountCodeSelect = document.getElementById('discount_code_id');
        if (discountCodeSelect.value) {
            const selectedOption = discountCodeSelect.options[discountCodeSelect.selectedIndex];
            const discountType = selectedOption.getAttribute('data-type');
            const discountValue = parseFloat(selectedOption.getAttribute('data-value'));

            console.log('Discount Type:', discountType, 'Discount Value:', discountValue);

            if (discountType === 'amount') {
                discountAmount = discountValue;
            } else if (discountType === 'percentage') {
                discountAmount = subTotal * (discountValue / 100);
            }
        }

        const total = subTotal + tax + totalShipping - discountAmount;

        console.log('Sub Total:', subTotal, 'Tax:', tax, 'Total Shipping:', totalShipping, 'Discount:', discountAmount, 'Total:', total);

        document.getElementById('sub_total').textContent = formatCurrency(subTotal);
        document.getElementById('tax').textContent = formatCurrency(tax);
        document.getElementById('total_shipping').textContent = formatCurrency(totalShipping);
        document.getElementById('discount_amount').textContent = formatCurrency(discountAmount);
        document.getElementById('total').textContent = formatCurrency(total);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // Gắn sự kiện để cập nhật tổng tiền
    document.getElementById('products').addEventListener('change', updateTotals);
    document.getElementById('products').addEventListener('input', updateTotals);
    document.getElementById('discount_code_id').addEventListener('change', updateTotals);
    document.getElementById('add-product').addEventListener('click', () => setTimeout(updateTotals, 0));

    // Khởi tạo tổng tiền ban đầu
    updateTotals();

    document.getElementById('user_type').addEventListener('change', updateAddressOptions);
    document.getElementById('user_id').addEventListener('change', updateAddressOptions);

    initializeAddressSelects();
    updateRemoveButtons();

    // Thêm sự kiện cho việc thay đổi sản phẩm và số lượng
    document.addEventListener('change', function (event) {
        if (event.target.name && event.target.name.startsWith('products')) {
            updateTotals();
        }
    });

    // Thêm sự kiện cho việc thay đổi phí vận chuyển
    function addEventListeners() {
        document.querySelectorAll('select[name^="products"][name$="[id]"]').forEach(select => {
            select.addEventListener('change', updateTotals);
        });

        document.querySelectorAll('input[name^="products"][name$="[quantity]"]').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        document.querySelectorAll('input[name^="shipping_addresses"][name$="[ship_charge]"]').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        document.getElementById('discount_code_id').addEventListener('change', updateTotals);
    }

    // Gọi hàm này sau khi thêm sản phẩm mới và khi trang được tải
    addEventListeners();
</script>