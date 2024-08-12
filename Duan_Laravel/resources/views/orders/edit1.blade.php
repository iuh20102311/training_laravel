<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('Cập nhật hóa đơn') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('orders.update', $order->order_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex space-x-6">
                <div class="flex-grow w-2/3 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Có lỗi xảy ra!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Khung thông tin khách hàng -->
                    <div class="mb-6 p-4 border rounded-lg border-black">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin khách hàng</h3>
                        <!-- <div class="flex items-center mb-4 space-x-4 rounded-lg flex-col sm:flex-row">
                            <div class="flex-1">
                                <label for="user_search" class="block text-sm font-medium text-gray-700">Tìm khách hàng:</label>
                                <select id="user_search" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="{{ $order->user_id }}">{{ $order->user->name }} ({{ $order->user->email }})</option>
                                </select>
                                <input type="hidden" id="user_id" name="user_id" value="{{ $order->user_id }}">
                            </div>
                            <div class="flex items-center space-x-2 mt-6">
                                <input type="checkbox" id="new_user_checkbox" class="form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                <label for="new_user_checkbox" class="text-sm font-medium text-gray-700">Khách hàng mới</label>
                            </div>
                        </div> -->
                        <div class="flex-1">
                            <label for="user_search" class="block text-sm font-medium text-gray-700">Khách hàng:</label>
                            <input class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $order->user->name }} ({{ $order->user->email }})" disabled>
                            <input type="hidden" id="user_id" name="user_id" value="{{ $order->user_id }}">
                        </div>

                        <div class="flex space-x-6 mb-2">
                            <div class="w-full">
                                <label for="user_name" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" id="user_name" name="user_name" value="{{ $order->user_name }}" class="mt-1 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2" disabled>
                            </div>
                            <div class="w-full">
                                <label for="user_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="user_email" name="user_email" value="{{ $order->user_email }}" class="mt-1 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Khung thông tin giao hàng -->
                    <div class="mb-6 p-4 border rounded-lg" style="border: 1px solid black; border-radius: 0.375rem;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin giao hàng</h3>
                        <div id="shipping_addresses">
                            @foreach($order->shippingAddresses as $index => $address)
                                <div class="shipping-address mb-4 p-4 border rounded-lg" data-index="{{ $index }}">
                                    <div class="flex items-center mb-4 space-x-4">
                                        <div class="flex-1">
                                            <label for="address_select_{{ $index }}" class="block text-sm font-medium text-gray-700">Chọn địa chỉ</label>
                                            <select id="address_select_{{ $index }}" class="address-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Chọn địa chỉ</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center space-x-2 mt-6">
                                            <input type="checkbox" class="new-address-checkbox form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                            <label for="new_address_checkbox" class="text-sm font-medium text-gray-700">Địa chỉ mới</label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-4">
                                        <div class="flex flex-col sm:flex-row space-x-4">
                                            <div class="flex-1">
                                                <label for="address_{{ $index }}" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                                <input type="text" id="address_{{ $index }}" name="shipping_addresses[{{ $index }}][address]" value="{{ $address->address }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            </div>
                                            <div class="flex-1">
                                                <label for="city_{{ $index }}" class="block text-sm font-medium text-gray-700">Thành phố</label>
                                                <input type="text" id="city_{{ $index }}" name="shipping_addresses[{{ $index }}][city]" value="{{ $address->city }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row space-x-4">
                                            <div class="flex-1">
                                                <label for="district_{{ $index }}" class="block text-sm font-medium text-gray-700">Quận/Huyện</label>
                                                <input type="text" id="district_{{ $index }}" name="shipping_addresses[{{ $index }}][district]" value="{{ $address->district }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            </div>
                                            <div class="flex-1">
                                                <label for="ward_{{ $index }}" class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                                                <input type="text" id="ward_{{ $index }}" name="shipping_addresses[{{ $index }}][ward]" value="{{ $address->ward }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            </div>
                                            <div class="flex-1">
                                                <label for="phone_{{ $index }}" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                                <input type="text" id="phone_{{ $index }}" name="shipping_addresses[{{ $index }}][phone_number]" value="{{ $address->phone_number }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Danh sách sản phẩm -->
                                    <div class="mb-4 mt-4">
                                        <label class="block text-sm font-bold text-gray-700">Danh sách sản phẩm</label>
                                        <div class="flex items-center">
                                            <select id="product_select_{{ $index }}" class="product-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->product_id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}">
                                                        {{ $product->name }} ({{ number_format($product->price) }} VND)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="add-product ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Thêm
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Table product -->
                                    <div class="overflow-x-auto my-6">
                                        <table class="min-w-full divide-y divide-gray-200 table-auto w-full bg-white rounded-lg shadow-lg">
                                            <tbody id="product_list_{{ $index }}" class="bg-white divide-y divide-gray-200">
                                                <tr class="product-table-header bg-red-500 text-white">
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Hình ảnh</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Tên</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">SKU</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Giá</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Số lượng</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Thao tác</th>
                                                </tr>
                                                @foreach($address->orderDetails as $detail)
                                                    <tr class="product-row" data-product-id="{{ $detail->product_id }}">
                                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" class="w-12 h-12">
                                                        </td>
                                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ $detail->product_name }}</td>
                                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ $detail->product_id }}</td>
                                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ number_format($detail->price_buy) }} VND</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center justify-center">
                                                                <button type="button" class="decrement-quantity px-3 py-1 bg-gray-300 rounded-l">-</button>
                                                                <input type="number" name="shipping_addresses[{{ $index }}][products][{{ $detail->product_id }}][quantity]" value="{{ $detail->quantity }}" min="1" max="20" class="quantity-input mt-1 w-20 shadow-sm border rounded-md text-center" readonly>
                                                                <button type="button" class="increment-quantity px-3 py-1 bg-gray-300 rounded-r">+</button>
                                                                <input type="hidden" name="shipping_addresses[{{ $index }}][products][{{ $detail->product_id }}][product_id]" value="{{ $detail->product_id }}">
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <button type="button" class="remove-product text-white px-6 py-2 bg-red-500 rounded">Xóa</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                        <input type="number" name="shipping_addresses[{{ $index }}][ship_charge]" value="{{ $address->ship_charge }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2" required min="0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" id="add_shipping_address" class="mt-2 mb-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Thêm địa chỉ giao hàng mới
                    </button>
                </div>

                <!-- Khung tổng tiền -->
                <div class="w-1/3 ml-4 flex-grow bg-white sm:rounded-lg p-6">
                    <div class="sticky top-0">
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Hình thức thanh toán (Payment Method)</label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="1" {{ $order->payment_method == 1 ? 'selected' : '' }}>COD</option>
                                <option value="2" {{ $order->payment_method == 2 ? 'selected' : '' }}>PayPal</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="discount_code_id" class="block text-sm font-medium text-gray-700">Discount Code</label>
                            <select name="discount_code_id" id="discount_code_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">No Discount</option>
                                @foreach($discountCodes as $discountCode)
                                    <option value="{{ $discountCode['id'] }}"
                                            data-type="{{ $discountCode['type'] }}"
                                            data-amount="{{ $discountCode['amount'] }}"
                                            data-percente="{{ $discountCode['percentage'] }}"
                                            {{ $order->discount_code_id == $discountCode['id'] ? 'selected' : '' }}>
                                        {{ $discountCode['code'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                            <h3 class="text-xl font-semibold mb-4 text-blue-700">Tổng cộng</h3>
                            <div class="space-y-2">
                                <p class="flex justify-between"><span>Tạm tính:</span> <span id="sub_total">0 VND</span></p>
                                <p class="flex justify-between"><span>Thuế (10%):</span> <span id="tax">0 VND</span></p>
                                <p class="flex justify-between"><span>Phí vận chuyển:</span> <span id="total_shipping">0 VND</span></p>
                                <p id="discount_row" class="flex justify-between"><span>Giảm giá:</span> <span id="discount">0 VND</span></p>
                                <p class="flex justify-between font-semibold text-lg"><span>Tổng cộng:</span> <span id="total">0 VND</span></p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center space-x-2">
                            <a href="{{ route('orders.index') }}" class="items-center px-6 py-2 ml-4 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Quay lại
                            </a>
                            <button type="submit" class="items-center px-6 py-2 border border-transparent text-sm font-bold font rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Cập nhật đơn hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateTotals();

        const products = @json($products);
        let shippingAddressCount = {{ count($order->shippingAddresses) }};

        const userSearch = document.getElementById('user_search');
        const userIdInput = document.getElementById('user_id');
        const userNameInput = document.getElementById('user_name');
        const userEmailInput = document.getElementById('user_email');
        const newUserCheckbox = document.getElementById('new_user_checkbox');

        // Initialize Select2
        $(userSearch).select2({
            placeholder: 'Nhập khách hàng cần tìm',
            allowClear: true,
            data: users.map(user => ({
                id: user.id,
                text: `${user.name} (${user.email})`,
                user: user
            })),
            minimumInputLength: 0,
        });

        // Disable address selects initially
        disableAddressSelects(true);

        // Handle user selection
        $(userSearch).on('select2:select', function (e) {
            const user = e.params.data.user;
            selectUser(user);
        });

        // Handle clearing user selection
        $(userSearch).on('select2:clear', function () {
            clearUserInfo();
            disableAddressSelects(true);
        });

        function selectUser(user) {
            userIdInput.value = user.id;
            userNameInput.value = user.name;
            userEmailInput.value = user.email;
            updateAddressOptions(user.addresses);
            disableAddressSelects(false);
        }

        function clearUserInfo() {
            userIdInput.value = '';
            userNameInput.value = '';
            userEmailInput.value = '';
            updateAddressOptions([]);
            disableAddressSelects(true);
        }

        newUserCheckbox.addEventListener('change', function () {
            const isNewUser = this.checked;
            userSearch.value = '';

            clearUserInfo();
            disableUserInputs(!isNewUser);
            disableAddressSelects(true);
            $(userSearch).prop('disabled', isNewUser).trigger('change');

            // Update Select2 after change
            $(userSearch).select2('destroy').select2({
                placeholder: 'Nhập khách hàng cần tìm',
                allowClear: true,
                data: users.map(user => ({
                    id: user.id,
                    text: `${user.name} (${user.email})`,
                    user: user
                })),
                minimumInputLength: 0,
            });

            if (isNewUser) {
                const addressDivs = document.querySelectorAll('.shipping-address');
                addressDivs.forEach(div => {
                    updateAddressFieldsState(div, true, true);
                });
            }
        });

        function disableUserInputs(disabled) {
            userNameInput.readOnly = disabled;
            userEmailInput.readOnly = disabled;
        }

        function updateAddressOptions(addresses) {
            const addressSelects = document.querySelectorAll('.address-select');
            addressSelects.forEach(select => {
                select.innerHTML = '<option value="">Chọn địa chỉ</option>';
                addresses.forEach(address => {
                    const option = document.createElement('option');
                    option.value = address.id;
                    option.textContent = `${address.address}, ${address.ward}, ${address.district}, ${address.city}`;
                    select.appendChild(option);
                });
            });
        }

        function disableAddressSelects(disable) {
            const addressSelects = document.querySelectorAll('.address-select');
            addressSelects.forEach(select => {
                select.disabled = disable;
            });
        }

        function updateAddressFieldsState(addressDiv, isEnabled, isNewAddress = false) {
            const inputs = addressDiv.querySelectorAll('input[type="text"]');
            const addressSelect = addressDiv.querySelector('.address-select');
            const newAddressCheckbox = addressDiv.querySelector('.new-address-checkbox');

            if (isNewAddress) {
                addressSelect.disabled = true;
                addressSelect.value = '';
                inputs.forEach(input => {
                    input.disabled = false;
                    input.value = '';
                });
                newAddressCheckbox.disabled = false;
            } else {
                addressSelect.disabled = !isEnabled;
                inputs.forEach(input => {
                    input.disabled = !isEnabled;
                });
            }

            if (!isEnabled) {
                newAddressCheckbox.checked = false;
                addressSelect.value = '';
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
        }

        // Event delegation for address select and new address checkbox
        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('address-select')) {
                const addressId = event.target.value;
                const addressDiv = event.target.closest('.shipping-address');
                const addressIndex = addressDiv.dataset.index;
                const selectedUser = users.find(u => u.id == userIdInput.value);
                const address = selectedUser ? selectedUser.addresses.find(a => a.id == addressId) : null;

                if (address) {
                    addressDiv.querySelector(`#address_${addressIndex}`).value = address.address;
                    addressDiv.querySelector(`#city_${addressIndex}`).value = address.city;
                    addressDiv.querySelector(`#district_${addressIndex}`).value = address.district;
                    addressDiv.querySelector(`#ward_${addressIndex}`).value = address.ward;
                    addressDiv.querySelector(`#phone_${addressIndex}`).value = address.phone_number;

                    const inputs = addressDiv.querySelectorAll('input[type="text"]');
                    inputs.forEach(input => {
                        input.disabled = false;
                    });
                } else {
                    const inputs = addressDiv.querySelectorAll('input[type="text"]');
                    inputs.forEach(input => {
                        input.value = '';
                        input.disabled = true;
                    });
                }
            } else if (event.target.classList.contains('new-address-checkbox')) {
                const addressDiv = event.target.closest('.shipping-address');
                const isNewAddress = event.target.checked;
                updateAddressFieldsState(addressDiv, true, isNewAddress);
            }
        });

        // Add new shipping address block
        document.getElementById('add_shipping_address').addEventListener('click', function () {
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
            productList.innerHTML = `
                <tr class="product-table-header bg-red-500 text-white">
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Số lượng</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Thao tác</th>
                </tr>
            `;

            // Update the product select ID
            const productSelect = newShippingAddressDiv.querySelector('.product-select');
            productSelect.id = `product_select_${shippingAddressCount}`;

            // Update the add product button
            const addProductButton = newShippingAddressDiv.querySelector('.add-product');
            addProductButton.dataset.index = shippingAddressCount;

            // Reset and update the new address checkbox
            const newAddressCheckbox = newShippingAddressDiv.querySelector('.new-address-checkbox');
            newAddressCheckbox.checked = false;
            updateAddressFieldsState(newShippingAddressDiv, false);

            shippingAddressesDiv.appendChild(newShippingAddressDiv);

            // Populate address select options for the new address field
            if (userIdInput.value) {
                const selectedUser = users.find(u => u.id == userIdInput.value);
                if (selectedUser) {
                    updateAddressOptions(selectedUser.addresses);
                }
            }

            shippingAddressCount++;
        });

        // Event delegation for add product buttons
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-product')) {
                const index = e.target.closest('.shipping-address').dataset.index;
                addProduct(index);
            }
        });

        // Cập nhật tổng tiền khi thay đổi số lượng sản phẩm
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                updateTotals();
            }
        });

        // Cập nhật tổng tiền khi thay đổi phí ship
        document.addEventListener('input', function(e) {
            if (e.target.name.includes('[ship_charge]')) {
                updateTotals();
            }
        });

        // Cập nhật tổng tiền khi thay đổi mã giảm giá
        document.getElementById('discount_code_id').addEventListener('change', updateTotals);

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

                const existingRow = productList.querySelector(`tr[data-product-id="${product.id}"]`);

                if (existingRow) {
                    const quantityInput = existingRow.querySelector('.quantity-input');
                    let quantity = parseInt(quantityInput.value);
                    if (quantity < 20) {
                        quantityInput.value = quantity + 1;
                    }
                } else {
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

                    // Add event listeners for quantity buttons and remove button
                    row.querySelector('.decrement-quantity').addEventListener('click', function () {
                        let quantityInput = row.querySelector('.quantity-input');
                        let quantity = parseInt(quantityInput.value);
                        if (quantity > 1) {
                            quantityInput.value = quantity - 1;
                        }
                        updateTotals();
                    });

                    row.querySelector('.increment-quantity').addEventListener('click', function () {
                        let quantityInput = row.querySelector('.quantity-input');
                        let quantity = parseInt(quantityInput.value);
                        if (quantity < 20) {
                            quantityInput.value = quantity + 1;
                        }
                        updateTotals();
                    });

                    row.querySelector('.remove-product').addEventListener('click', function () {
                        row.remove();
                        updateTotals();
                    });
                }
                updateTotals();
            }
        }

        // Remove product from the product list
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-product')) {
                const row = e.target.closest('tr');
                const productList = row.closest('tbody');
                row.remove();

                // Remove the header if no products are left
                const remainingRows = productList.querySelectorAll('tr');
                if (remainingRows.length === 1 && remainingRows[0].classList.contains('product-table-header')) {
                    productList.innerHTML = '';
                }

                updateTotals();
            }
        });

        // Hàm cập nhật tổng tiền
        function updateTotals() {
            let subTotal = 0;
            let totalShipping = 0;
            const taxRate = 0.10;

            document.querySelectorAll('.shipping-address').forEach(addressDiv => {
                const productRows = addressDiv.querySelectorAll('.product-row');
                productRows.forEach(row => {
                    const price = parseFloat(row.querySelector('.product-price').textContent.replace(/[^\d]/g, ''));
                    const quantity = parseInt(row.querySelector('.quantity-input').value);
                    subTotal += price * quantity;
                });

                const shipCharge = parseFloat(addressDiv.querySelector('input[name$="[ship_charge]"]').value) || 0;
                totalShipping += shipCharge;
            });

            const discountSelect = document.getElementById('discount_code_id');
            let discount = 0;
            if (discountSelect.value) {
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const discountType = selectedOption.dataset.type;
                const discountValue = parseFloat(discountType === 'percentage' ? selectedOption.dataset.percente : selectedOption.dataset.amount);
                
                discount = discountType === 'percentage' ? (subTotal * discountValue / 100) : discountValue;
            }

            const tax = subTotal * taxRate;
            const total = subTotal + tax + totalShipping - discount;

            document.getElementById('sub_total').textContent = numberFormat(subTotal) + ' VND';
            document.getElementById('tax').textContent = numberFormat(tax) + ' VND';
            document.getElementById('total_shipping').textContent = numberFormat(totalShipping) + ' VND';
            document.getElementById('discount').textContent = numberFormat(discount) + ' VND';
            document.getElementById('total').textContent = numberFormat(total) + ' VND';
        }

        // Hàm định dạng số
        function numberFormat(number) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(number));
        }

        // Add event listeners for input fields
        document.addEventListener('input', function (e) {
            if (e.target.matches('input[type="number"]') || e.target.matches('input[name$="[ship_charge]"]')) {
                updateTotals();
            }
        });

        // Add event listener for discount code selection
        document.getElementById('discount_code_id').addEventListener('change', updateTotals);

        // // Number format function
        // function numberFormat(number) {
        //     return new Intl.NumberFormat('vi-VN', {
        //         minimumFractionDigits: 0,
        //         maximumFractionDigits: 0
        //     }).format(Math.round(number));
        // }

        // Initialize totals
        updateTotals();

        // Initialize address fields state
        document.querySelectorAll('.shipping-address').forEach(addressDiv => {
            updateAddressFieldsState(addressDiv, true);
        });
    });
</script>
