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

                    <div class="mb-4">
                        <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                        <select name="user_type" id="user_type"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="existing">Existing User</option>
                            <option value="new">New User</option>
                        </select>
                    </div>

                    <div id="existing_user_fields">
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="new_user_fields" style="display: none;">
                        <div class="mb-4">
                            <label for="new_user_name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="new_user_name" id="new_user_name"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="new_user_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="new_user_email" id="new_user_email"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                            Method</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="1">COD</option>
                            <option value="2">PayPal</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="discount_code_id" class="block text-sm font-medium text-gray-700">Discount
                            Code</label>
                        <select name="discount_code_id" id="discount_code_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">No Discount</option>
                            @foreach($discountCodes as $discountCode)
                                <option value="{{ $discountCode->id }}">{{ $discountCode->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Products</h3>
                    <div id="products">
                        <div class="product-item border-t border-gray-200 pt-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Product</label>
                                <select name="products[0][id]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->name }}
                                            ({{ number_format($product->price) }} VND)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="products[0][quantity]"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required min="1">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                <select name="shipping_addresses[0][type]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shipping-address-type"
                                    required>
                                    <option value="existing">Existing Address</option>
                                    <option value="new">New Address</option>
                                </select>
                            </div>
                            <div class="existing-address mb-4">
                                <select name="shipping_addresses[0][address_id]"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm existing-address-select"
                                    required>
                                    <option value="">Select an address</option>
                                </select>
                            </div>
                            <div class="new-address" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" name="shipping_addresses[0][address]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="shipping_addresses[0][city]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="shipping_addresses[0][district]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Ward</label>
                                    <input type="text" name="shipping_addresses[0][ward]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                                    <input type="text" name="shipping_addresses[0][phone_number]"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                <input type="number" name="shipping_addresses[0][ship_charge]"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    required>
                            </div>
                        </div>
                    </div>
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
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let productCount = 1;
    const users = @json($users);

    document.getElementById('add-product').addEventListener('click', function () {
        const productHtml = `
                <div class="product-item border-t border-gray-200 pt-4 mt-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="products[${productCount}][id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
                        <select name="shipping_addresses[${productCount}][type]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shipping-address-type" required>
                            <option value="existing">Existing Address</option>
                            <option value="new">New Address</option>
                        </select>
                    </div>
                    <div class="existing-address mb-4">
                        <select name="shipping_addresses[${productCount}][address_id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm existing-address-select" required>
                            <option value="">Select an address</option>
                        </select>
                    </div>
                    <div class="new-address" style="display: none;">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="shipping_addresses[${productCount}][address]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="shipping_addresses[${productCount}][city]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">District</label>
                            <input type="text" name="shipping_addresses[${productCount}][district]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Ward</label>
                            <input type="text" name="shipping_addresses[${productCount}][ward]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Shipping Phone</label>
                            <input type="text" name="shipping_addresses[${productCount}][phone_number]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                        <input type="number" name="shipping_addresses[${productCount}][ship_charge]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                </div>
            `;
        document.getElementById('products').insertAdjacentHTML('beforeend', productHtml);
        productCount++;
        initializeAddressSelects();
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

    document.getElementById('user_type').addEventListener('change', updateAddressOptions);
    document.getElementById('user_id').addEventListener('change', updateAddressOptions);

    initializeAddressSelects();
</script>