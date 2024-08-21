<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('Thêm hóa đơn mới') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('orders.store') }}" method="POST">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex space-x-6">
                <div class="flex-grow w-2/3 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
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
                    <!-- Khung thông tin khách hàng -->
                    <div class="mb-6 p-4 border rounded-lg border-black"
                        style="border: 1px solid black; border-radius: 0.375rem;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin khách hàng</h3>
                        <div class="flex items-center mb-4 space-x-4 rounded-lg flex-col sm:flex-row">
                            <!-- Phần input tìm khách hàng -->
                            <div class="flex-1">
                                <label for="user_search" class="block text-sm font-medium text-gray-700">Tìm khách
                                    hàng:</label>
                                <select id="user_search" value="{{ old('user_id') }}"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Tìm khách hàng</option>
                                </select>
                                <input type="hidden" id="user_id" name="user_id">
                                @error('user_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Phần checkbox -->
                            <div class="flex items-center space-x-2 mt-6">
                                <input type="checkbox" id="new_user_checkbox" {{ old('new_user_checkbox') ? 'checked' : '' }}
                                    class="form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                <label for="new_user_checkbox" class="text-sm font-medium text-gray-700">Khách hàng
                                    mới</label>
                            </div>
                        </div>

                        <div class="flex space-x-6 mb-2">
                            <div class="w-full">
                                <label for="user_name" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" id="user_name" name="user_name" value="{{ old('user_name') }}"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                @error('user_name')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="user_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="user_email" name="user_email" value="{{ old('user_email') }}"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                @error('user_email')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border rounded-lg" style="border: 1px solid black; border-radius: 0.375rem;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin giao hàng</h3>
                        <div id="shipping_addresses">
                            <div class="shipping-address mb-4 p-4 border rounded-lg" data-index="0">
                                <div class="flex items-center mb-4 space-x-4">
                                    <div class="flex-1">
                                        <label for="address_select_0"
                                            class="block text-sm font-medium text-gray-700">Chọn địa chỉ</label>
                                        <select id="address_select_0"
                                            class="address-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Chọn địa chỉ</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center space-x-2 mt-6">
                                        <input type="checkbox" {{ old('new-address-checkbox') ? 'checked' : '' }}
                                            class="new-address-checkbox form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                        <label for="new_address_checkbox" class="text-sm font-medium text-gray-700">Địa
                                            chỉ
                                            mới</label>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-4">
                                    <div class="flex flex-col sm:flex-row space-x-4">
                                        <div class="flex-1">
                                            <label for="address_0" class="block text-sm font-medium text-gray-700">Địa
                                                chỉ</label>
                                            <input type="text" id="address_0" name="shipping_addresses[0][address]"
                                                value="{{ old('shipping_addresses.0.address') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            @error('shipping_addresses.*.address')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label for="city_0" class="block text-sm font-medium text-gray-700">Thành
                                                phố</label>
                                            <input type="text" id="city_0" name="shipping_addresses[0][city]"
                                                value="{{ old('shipping_addresses.0.city') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            @error('shipping_addresses.*.city')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row space-x-4">
                                        <div class="flex-1">
                                            <label for="district_0"
                                                class="block text-sm font-medium text-gray-700">Quận/Huyện</label>
                                            <input type="text" id="district_0" name="shipping_addresses[0][district]"
                                                value="{{ old('shipping_addresses.0.district') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            @error('shipping_addresses.*.district')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label for="ward_0"
                                                class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                                            <input type="text" id="ward_0" name="shipping_addresses[0][ward]"
                                                value="{{ old('shipping_addresses.0.ward') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            @error('shipping_addresses.*.ward')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label for="phone_0" class="block text-sm font-medium text-gray-700">Số điện
                                                thoại</label>
                                            <input type="text" id="phone_0" name="shipping_addresses[0][phone_number]"
                                                value="{{ old('shipping_addresses.0.phone_number') }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                            @error('shipping_addresses.*.phone_number')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-4 mt-4">
                                    <label class="block text-sm font-bold text-gray-700">Danh sách sản phẩm</label>
                                    <div class="flex items-center">
                                        <select id="product_select_0" class="product-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                                    @error('shipping_addresses.*.products')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Table product -->
                                <div class="overflow-x-auto my-6">
                                    <table
                                        class="min-w-full divide-y divide-gray-200 table-auto w-full bg-white rounded-lg shadow-lg">
                                        <tbody id="product_list_0" class="bg-white divide-y divide-gray-200">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Shipping Charge</label>
                                    <input type="number" name="shipping_addresses[0][ship_charge]" value="{{ old('shipping_addresses.0.ship_charge') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2"
                                        required min="0">
                                    @error('shipping_addresses.*.ship_charge')
                                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="button" id="add_shipping_address"
                        class="mt-2 mb-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Thêm địa chỉ giao hàng mới
                    </button>


                </div>

                <!-- Khung tổng tiền -->
                <div class="w-1/3 ml-4 flex-grow bg-white sm:rounded-lg p-6">
                    <div class="sticky top-0">
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Hình thức thanh
                                toán
                                (Payment Method)</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="1" {{ old('payment_method') == '1' ? 'selected' : '' }}>COD</option>
                                <option value="2" {{ old('payment_method') == '2' ? 'selected' : '' }}>PayPal</option>
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
                                    <option value="{{ $discountCode['id'] }}"
                                            data-type="{{ $discountCode['type'] }}"
                                            data-amount="{{ $discountCode['amount'] }}"
                                            data-percente=" {{ $discountCode['percentage'] }} "
                                            {{ old('discount_code_id') == $discountCode['id'] ? 'selected' : '' }}>
                                        {{ $discountCode['code'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                            <h3 class="text-xl font-semibold mb-4 text-blue-700">Tổng cộng</h3>
                            <div class="space-y-2">
                                <p class="flex justify-between"><span>Tạm tính:</span> <span id="sub_total">0
                                        VND</span></p>
                                <p class="flex justify-between"><span>Thuế (10%):</span> <span id="tax">0
                                        VND</span></p>
                                <p class="flex justify-between"><span>Phí vận chuyển:</span> <span id="total_shipping">0
                                        VND</span></p>
                                <p id="discount_row" class="flex justify-between"><span>Giảm giá:</span> <span
                                        id="discount">0 VND</span></p>
                                <p class="flex justify-between font-semibold text-lg"><span>Tổng cộng:</span>
                                    <span id="total">0 VND</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center space-x-2">
                            <a href="{{ route('orders.index') }}"
                                class="items-center px-6 py-2 ml-4 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Quay lại
                            </a>
                            <button type="submit"
                                class="items-center px-6 py-2 border border-transparent text-sm font-bold font rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Thêm đơn hàng
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
        const users = @json($users);
        const products = @json($products);
        let shippingAddressCount = 1;

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
            sorter: function (data) {
                return data.slice(0, 10);
            }
        });
        
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
            userNameInput.value = user.name || (oldData ? oldData.user_name : '');
            userEmailInput.value = user.email || (oldData ? oldData.user_email : '');
            updateAddressOptions(user.addresses);
            initializeAddressFields();
        }

        function clearUserInfo() {
            userIdInput.value = '';
            userNameInput.value = '';
            userEmailInput.value = '';
            updateAddressOptions([]);
            resetAddressFields();
        }

        function resetAddressFields() {
            document.querySelectorAll('.shipping-address').forEach(addressDiv => {
                const inputs = addressDiv.querySelectorAll('input[type="text"]');
                inputs.forEach(input => {
                    input.value = '';
                    input.disabled = true;
                });
                const addressSelect = addressDiv.querySelector('.address-select');
                addressSelect.value = '';
                addressSelect.disabled = true;
            });
        }

        newUserCheckbox.addEventListener('change', function () {
        const isNewUser = this.checked;
        
        if (isNewUser) {
            // Xóa dữ liệu đang có
            clearUserInfo();
            
            // Vô hiệu hóa select chọn người dùng
            $(userSearch).prop('disabled', true).val(null).trigger('change');
            
            // Bỏ vô hiệu hóa input name và email
            userNameInput.disabled = false;
            userEmailInput.disabled = false;
            userNameInput.readOnly = false;
            userEmailInput.readOnly = false;
            
            // Chọn checkbox new address cho tất cả các địa chỉ và vô hiệu hóa nó
            document.querySelectorAll('.new-address-checkbox').forEach(checkbox => {
                checkbox.checked = true;
                checkbox.disabled = true; // Vô hiệu hóa checkbox
                const addressDiv = checkbox.closest('.shipping-address');
                updateAddressFieldsState(addressDiv, true, true);
            });
        } else {
            // Khi bỏ chọn New User
            if (userNameInput.value || userEmailInput.value) {
                Swal.fire({
                    title: 'Xác nhận xóa thông tin?',
                    text: "Bạn có chắc muốn xóa thông tin đã nhập không?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performNonNewUserActions();
                    } else {
                        this.checked = true;
                    }
                });
            } else {
                performNonNewUserActions();
            }
        }

        function performNonNewUserActions() {
            // Xóa dữ liệu đang có
            clearUserInfo();
            
            // Cho phép chọn select user
            $(userSearch).prop('disabled', false).trigger('change');
            
            // Vô hiệu hóa input name và email
            userNameInput.disabled = true;
            userEmailInput.disabled = true;
            userNameInput.readOnly = true;
            userEmailInput.readOnly = true;
            
            // Bỏ chọn checkbox new address cho tất cả các địa chỉ và bỏ vô hiệu hóa
            document.querySelectorAll('.new-address-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = false; // Bỏ vô hiệu hóa checkbox
                const addressDiv = checkbox.closest('.shipping-address');
                updateAddressFieldsState(addressDiv, false, false);
            });
        }
    });

        function disableUserInputs(disabled) {
            userNameInput.readOnly = disabled;
            userEmailInput.readOnly = disabled;
            userNameInput.disabled = disabled;
            userEmailInput.disabled = disabled;
        }

        userNameInput.disabled = true;
        userEmailInput.disabled = true;

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
                select.disabled = disable && !userIdInput.value;
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
                });
            } else {
                addressSelect.disabled = !userIdInput.value;
                if (isEnabled && (addressSelect.value || inputs[0].value)) {
                    inputs.forEach(input => {
                        input.disabled = false;
                    });
                } else {
                    inputs.forEach(input => {
                        input.disabled = !input.value;
                    });
                }
                newAddressCheckbox.disabled = false; // Bỏ vô hiệu hóa khi không phải new user
            }
        }

        var oldData = @json(old());
        var oldUserId = @json(old('user_id'));
        function initializeAddressFields() {
            const selectedUserId = userIdInput.value || oldUserId;
            const selectedUser = users.find(u => u.id == selectedUserId);

            document.querySelectorAll('.shipping-address').forEach((addressDiv, index) => {
                const addressSelect = addressDiv.querySelector('.address-select');
                const inputs = addressDiv.querySelectorAll('input[type="text"]');
                const newAddressCheckbox = addressDiv.querySelector('.new-address-checkbox');

                // Populate address options if a user is selected
                if (selectedUser) {
                    updateAddressOptions(selectedUser.addresses);
                    addressSelect.disabled = false;
                } else {
                    addressSelect.disabled = true;
                }

                // Set the selected address if it exists in old input
                const oldAddressId = oldData && oldData.shipping_addresses && oldData.shipping_addresses[index] ? oldData.shipping_addresses[index].address_id : null;
                if (oldAddressId) {
                    addressSelect.value = oldAddressId;
                    // Trigger change event to update input fields
                    const changeEvent = new Event('change');
                    addressSelect.dispatchEvent(changeEvent);
                }

                if (newAddressCheckbox.checked) {
                    updateAddressFieldsState(addressDiv, true, true);
                } else if (addressSelect.value) {
                    updateAddressFieldsState(addressDiv, true, false);
                } else {
                    inputs.forEach(input => {
                        input.disabled = !input.value;
                    });
                }
            });
        }

        // Set initial user selection if exists
        if (oldUserId) {
            const initialUser = users.find(u => u.id == oldUserId);
            if (initialUser) {
                $(userSearch).val(oldUserId).trigger('change');
                selectUser(initialUser);
            }
        }

        // Initialize address fields
        initializeAddressFields();

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
                const inputs = addressDiv.querySelectorAll('input[type="text"]');
                const hasEnteredData = Array.from(inputs).some(input => input.value.trim() !== '');

                updateAddressFieldsState(addressDiv, true, isNewAddress);

                if (!isNewAddress && hasEnteredData) {
                    // Hiển thị thông báo xác nhận trước khi xóa thông tin
                    Swal.fire({
                        title: 'Xác nhận xóa thông tin?',
                        text: "Bạn có chắc muốn xóa thông tin đã nhập không?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Nếu người dùng xác nhận xóa
                            updateAddressFieldsState(addressDiv, true, false);
                            inputs.forEach(input => {
                                input.value = '';
                                input.disabled = true;
                            });
                            addressDiv.querySelector('.address-select').disabled = false;
                        } else {
                            // Nếu người dùng không xác nhận, đặt lại checkbox
                            event.target.checked = true;
                        }
                    });
                } else {
                    updateAddressFieldsState(addressDiv, true, isNewAddress);
                    inputs.forEach(input => {
                        input.value = '';
                        input.disabled = !isNewAddress;
                    });

                    const addressSelect = addressDiv.querySelector('.address-select');
                    addressSelect.disabled = isNewAddress;
                    if (isNewAddress) {
                        addressSelect.value = '';
                    }
                }
            }
        });

        // Add new shipping address block
        document.getElementById('add_shipping_address').addEventListener('click', function () {
            const shippingAddressesDiv = document.getElementById('shipping_addresses');
            const newShippingAddressDiv = document.querySelector('.shipping-address').cloneNode(true);

            newShippingAddressDiv.dataset.index = shippingAddressCount;

            // Reset all input fields
            newShippingAddressDiv.querySelectorAll('input, select').forEach(input => {
                input.id = input.id.replace('_0', `_${shippingAddressCount}`);
                input.name = input.name.replace('[0]', `[${shippingAddressCount}]`);
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
            newAddressCheckbox.checked = false;
            updateAddressFieldsState(newShippingAddressDiv, false); 

            shippingAddressesDiv.appendChild(newShippingAddressDiv);

            // Khi đã chọn checkbox new user thì có thêm địa chỉ giao hàng mới vẫn tự động chọn checkbox address và vô hiệu hóa nó
            if (newUserCheckbox.checked) {
                const newAddressCheckbox = newShippingAddressDiv.querySelector('.new-address-checkbox');
                newAddressCheckbox.checked = true;
                newAddressCheckbox.disabled = true;
                updateAddressFieldsState(newShippingAddressDiv, true, true);
            }

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

        $(document).ready(function() {
            $('.product-select').select2({
                placeholder: 'Chọn sản phẩm',
                allowClear: true,
                width: '100%'
            });

            // Khi thêm sản phẩm mới, cần khởi tạo lại Select2
            $(document).on('click', '.add-product', function() {
                let index = $(this).closest('.shipping-address').data('index');
                addProduct(index);
                $(`#product_select_${index}`).select2({
                    placeholder: 'Chọn sản phẩm',
                    allowClear: true,
                    width: '100%'
                });
            });
        });

        function addProduct(index) {
            const productSelect = document.getElementById(`product_select_${index}`);
            const productList = document.getElementById(`product_list_${index}`);
            const selectedOption = productSelect.options[productSelect.selectedIndex];

            // Check if the table has content
            if (productList.children.length === 0) {
                // If no content, add the table header
                const headerRow = document.createElement('tr');
                headerRow.classList.add('product-table-header', 'bg-red-500', 'text-white');
                headerRow.innerHTML = `
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Số lượng</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Thao tác</th>
                `;
                productList.appendChild(headerRow);
            }

            if (selectedOption.value) {
                const product = {
                    id: selectedOption.value,
                    name: selectedOption.dataset.name,
                    price: selectedOption.dataset.price,
                    image: selectedOption.dataset.image

                };
                
                $(`#product_select_${index}`).val(null).trigger('change');
                
                // Check if the product already exists in the table
                const existingRow = productList.querySelector(`tr[data-product-id="${product.id}"]`);

                if (existingRow) {
                    // If the product exists, increment the quantity
                    const quantityInput = existingRow.querySelector('.quantity-input');
                    let quantity = parseInt(quantityInput.value);
                    if (quantity
                        < 20) {
                        quantityInput.value = quantity + 1;
                    }
                } else {
                    // If the product doesn't exist, create a new row
                    const row = document.createElement('tr');
                    row.classList.add('hover:bg-gray-100');
                    row.dataset.productId = product.id; // Add data-product-id attribute

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
                                <input type="number" name="shipping_addresses[${index}][products][${product.id}][quantity]" value="1" min="1" max="20" class="quantity-input border border-gray-300 px-2 py-1 rounded-none text-center w-12 mx-2" readonly> 
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
                        updateTotals(); // Update totals after quantity change
                    });

                    row.querySelector('.increment-quantity').addEventListener('click', function () {
                        let quantityInput = row.querySelector('.quantity-input');
                        let quantity = parseInt(quantityInput.value);
                        if (quantity < 20) {
                            quantityInput.value = quantity + 1;
                        }
                        updateTotals(); // Update totals after quantity change
                    });

                    row.querySelector('.remove-product').addEventListener('click', function () {
                        row.remove();
                        updateTotals(); // Update totals after removing a product
                    });
                }

                // Cập nhật lại tổng tiền khi thêm sản phẩm
                updateTotals();
            }
        }

        // Remove product from the product list
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-product')) {
                const row = e.target.closest('tr');
                const productList = row.closest('tbody');
                row.parentNode.removeChild(row);

                // Remove the header if no products are left
                const remainingRows = productList.querySelectorAll('tr');
                if (remainingRows.length === 1 && remainingRows[0].classList.contains(
                    'product-table-header')) {
                    productList.removeChild(remainingRows[0]);
                }

                // Update total price
                updateTotals();
            }
        });

        // Hàm cập nhật tổng tiền
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

            // Áp dụng mã giảm giá
            const discountSelect = document.getElementById('discount_code_id');
            if (discountSelect.value) {
                const selectedOption = discountSelect.options[discountSelect.selectedIndex];
                const discountAmount = parseFloat(selectedOption.dataset.amount);
                const discountPercentage = parseFloat(selectedOption.dataset.percente);
                const discountType = selectedOption.dataset.type;

                if (discountType === 'percentage') {
                    discount = subTotal * (discountPercentage / 100);
                } else if (discountType === 'amount') {
                    discount = discountAmount;
                }
                console.log(discountType);
            }
            
            const tax = subTotal * taxRate;
            const total = subTotal + tax + totalShipping - discount;

            document.getElementById('sub_total').textContent = numberFormat(subTotal) + ' VND';
            document.getElementById('tax').textContent = numberFormat(tax) + ' VND';
            document.getElementById('total_shipping').textContent = numberFormat(totalShipping) + ' VND';
            document.getElementById('discount').textContent = numberFormat(discount) + ' VND';
            document.getElementById('total').textContent = numberFormat(total) + ' VND';

            console.log('Discount Value:', discount);

        }

        // Thêm sự kiện cho các trường nhập liệu
        document.addEventListener('input', function (e) {
            if (e.target.matches('input[type="number"]') || e.target.matches(
                'input[name$="[ship_charge]"]')) {
                updateTotals();
            }
        });

        // Thêm sự kiện cho việc chọn mã giảm giá
        document.getElementById('discount_code_id').addEventListener('change', updateTotals);

        // Format số thành dạng tiền tệ
        function numberFormat(number) {
            return new Intl.NumberFormat('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(number));
        }

        // Gọi updateTotals lần đầu để hiển thị giá trị ban đầu
        updateTotals();

        // Khởi tạo trạng thái ban đầu cho địa chỉ giao hàng đầu tiên
        const firstAddressDiv = document.querySelector('.shipping-address');
        // updateAddressFieldsState(firstAddressDiv, false);

        if (userIdInput.value) {
        disableAddressSelects(false);
        }
    });
</script>