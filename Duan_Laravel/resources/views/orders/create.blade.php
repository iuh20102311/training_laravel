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
                    <div class="mb-6 p-4 border rounded-lg" style="border: 1px solid black; border-radius: 0.375rem;">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Thông tin khách hàng</h3>
                        <div class="flex items-center mb-4 space-x-4">
                            <!-- Phần input tìm khách hàng -->
                            <div class="flex-1">
                                <label for="user_search" class="block text-sm font-medium text-gray-700">Tìm khách
                                    hàng:</label>
                                <input type="text" id="user_search"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                <input type="hidden" id="user_id" name="user_id">
                            </div>
                            <!-- Phần checkbox -->
                            <div class="flex items-center space-x-2 mt-6">
                                <input type="checkbox" id="new_user_checkbox"
                                    class="form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                <label for="new_user_checkbox" class="text-sm font-medium text-gray-700">Khách hàng
                                    mới</label>
                            </div>
                        </div>
                        <div class="flex space-x-6 mb-2">
                            <div class="w-full">
                                <label for="user_name" class="block text-sm font-medium text-gray-700">Tên</label>
                                <input type="text" id="user_name" name="user_name"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2"
                                    readonly>
                            </div>
                            <div class="w-full">
                                <label for="user_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="user_email" name="user_email"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2"
                                    readonly>
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
                                        <input type="checkbox"
                                            class="new-address-checkbox form-checkbox w-6 h-6 text-blue-500 border border-gray-300 rounded focus:ring focus:ring-blue-500">
                                        <label for="new_user_checkbox" class="text-sm font-medium text-gray-700">Địa
                                            chỉ
                                            mới</label>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <label for="address_0" class="block text-sm font-medium text-gray-700">Địa
                                            chỉ</label>
                                        <input type="text" id="address_0" name="shipping_addresses[0][address]"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label for="city_0" class="block text-sm font-medium text-gray-700">Thành
                                            phố</label>
                                        <input type="text" id="city_0" name="shipping_addresses[0][city]"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label for="district_0"
                                            class="block text-sm font-medium text-gray-700">Quận/Huyện</label>
                                        <input type="text" id="district_0" name="shipping_addresses[0][district]"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label for="ward_0"
                                            class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                                        <input type="text" id="ward_0" name="shipping_addresses[0][ward]"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label for="phone_0" class="block text-sm font-medium text-gray-700">Số điện
                                            thoại</label>
                                        <input type="text" id="phone_0" name="shipping_addresses[0][phone_number]"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md px-3 py-2">
                                    </div>
                                </div>

                                <div class="mb-4 mt-4">
                                    <label class="block text-sm font-bold text-gray-700">Danh sách sản phẩm</label>
                                    <div class="flex items-center">
                                        <select id="product_select_0"
                                            class="product-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->product_id }}" data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}" data-image="{{ $product->image }}">
                                                {{ $product->name }} ({{ number_format($product->price) }} VND)
                                            </option>
                                            @endforeach
                                        </select>
                                        <button type="button"
                                            class="add-product ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Thêm
                                        </button>
                                    </div>
                                </div>

                                <div class="my-6">
                                    <table
                                        class="min-w-full divide-y divide-gray-200 table-auto w-full bg-white rounded-lg shadow-lg">
                                        <tbody id="product_list_0" class="bg-white divide-y divide-gray-200">
                                        </tbody>
                                    </table>
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
                            </div>
                        </div>
                    </div>


                    <button type="button" id="add_shipping_address"
                        class="mt-2 mb-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Thêm địa chỉ giao hàng mới
                    </button>


                </div>

                <!-- Khung tổng tiền -->
                <div class="w-1/3 ml-4">
                    <div class="sticky top-0">
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Hình thức thanh
                                toán
                                (Payment Method)</label>
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

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('orders.index') }}"
                                class="items-center px-6 py-4 ml-4 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Quay lại
                            </a>
                            <button type="submit"
                                class="items-center px-6 py-4 border border-transparent text-sm font-bold font rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Lưu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const users = @json($users);
    const products = @json($products);
    let shippingAddressCount = 1;

    // Xử lý tìm kiếm khách hàng
    const userSearch = document.getElementById('user_search');
    const userIdInput = document.getElementById('user_id');
    const userNameInput = document.getElementById('user_name');
    const userEmailInput = document.getElementById('user_email');
    const newUserCheckbox = document.getElementById('new_user_checkbox');

    userSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const matchedUsers = users.filter(user =>
            user.name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm)
        );

        // Hiển thị danh sách khách hàng phù hợp
        const userList = document.createElement('ul');
        matchedUsers.forEach(user => {
            const li = document.createElement('li');
            li.textContent = `${user.name} (${user.email})`;
            li.addEventListener('click', () => selectUser(user));
            userList.appendChild(li);
        });

        // Xóa danh sách cũ và thêm danh sách mới
        const oldList = userSearch.nextElementSibling;
        if (oldList && oldList.tagName === 'UL') {
            oldList.remove();
        }
        userSearch.parentNode.insertBefore(userList, userSearch.nextSibling);
    });

    function selectUser(user) {
        userIdInput.value = user.id;
        userNameInput.value = user.name;
        userEmailInput.value = user.email;
        userSearch.value = user.name;

        // Xóa danh sách gợi ý
        const userList = userSearch.nextElementSibling;
        if (userList && userList.tagName === 'UL') {
            userList.remove();
        }

        // Cập nhật danh sách địa chỉ
        updateAddressOptions(user.addresses);
    }

    newUserCheckbox.addEventListener('change', function() {
        const isNewUser = this.checked;
        userSearch.disabled = isNewUser;
        userNameInput.readOnly = !isNewUser;
        userEmailInput.readOnly = !isNewUser;

        if (isNewUser) {
            userIdInput.value = '';
            userNameInput.value = '';
            userEmailInput.value = '';
            updateAddressOptions([]);
        }
    });

    function updateAddressOptions(addresses) {
        const addressSelects = document.querySelectorAll('.address-select');
        addressSelects.forEach(select => {
            select.innerHTML = '<option value="">Chọn địa chỉ</option>';
            addresses.forEach(address => {
                const option = document.createElement('option');
                option.value = address.id;
                option.textContent =
                    `${address.address}, ${address.ward}, ${address.district}, ${address.city}`;
                select.appendChild(option);
            });
        });
    }

    // Hàm để cập nhật trạng thái của các trường input địa chỉ
    function updateAddressFieldsState(addressDiv, isNewAddress) {
        const inputs = addressDiv.querySelectorAll('input[type="text"], input[type="email"]');
        const addressSelect = addressDiv.querySelector('.address-select');

        inputs.forEach(input => {
            input.readOnly = !isNewAddress;
        });

        if (isNewAddress) {
            addressSelect.disabled = true;
            addressSelect.value = '';
            inputs.forEach(input => input.value = '');
        } else {
            addressSelect.disabled = false;
        }
    }

    // Event delegation cho địa chỉ và checkbox địa chỉ mới
    document.addEventListener('change', function(event) {
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
            }
        } else if (event.target.classList.contains('new-address-checkbox')) {
            const addressDiv = event.target.closest('.shipping-address');
            updateAddressFieldsState(addressDiv, event.target.checked);
        }
    });

    // Add new shipping address block
    document.getElementById('add_shipping_address').addEventListener('click', function() {
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
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-product')) {
            const index = e.target.closest('.shipping-address').dataset.index;
            addProduct(index);
        }
    });

    function addProduct(index) {
        const productSelect = document.getElementById(`product_select_${index}`);
        const productList = document.getElementById(`product_list_${index}`);
        const selectedOption = productSelect.options[productSelect.selectedIndex];

        // Check if the table has content
        if (productList.children.length === 0) {
            // If no content, add the table header
            const headerRow = document.createElement('tr');
            headerRow.classList.add('product-table-header', 'bg-red-500',
            'text-white'); // Add a class to the header row
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

            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-100');

            row.innerHTML = `
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <img src="{{ asset('storage/') }}/${product.image}" alt="${product.name}" class="w-12 h-12">
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">${product.name}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">${product.id}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">${numberFormat(product.price)} VND</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <input type="number" name="shipping_addresses[${index}][products][${product.id}][quantity]" value="1"
                            class="mt-1 w-20 shadow-sm border rounded-md"> 
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <button type="button" class="remove-product text-white px-6 py-2 bg-red-500 rounded">Xóa</button>
                    </td>
                `;

            productList.appendChild(row);

            // Update total price
            updateTotals();
        }
    }

    // Remove product from the product list
    document.addEventListener('click', function(e) {
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
            const discountType = selectedOption.dataset.type;
            const discountValue = parseFloat(selectedOption.dataset.value);

            if (discountType === 'percentage') {
                discount = subTotal * (discountValue / 100);
            } else {
                discount = discountValue;
            }
        }

        const tax = subTotal * taxRate;
        const total = subTotal + tax + totalShipping - discount;

        document.getElementById('sub_total').textContent = numberFormat(subTotal) + ' VND';
        document.getElementById('tax').textContent = numberFormat(tax) + ' VND';
        document.getElementById('total_shipping').textContent = numberFormat(totalShipping) + ' VND';
        document.getElementById('discount').textContent = numberFormat(discount) + ' VND';
        document.getElementById('total').textContent = numberFormat(total) + ' VND';
    }

    // Thêm sự kiện cho các trường nhập liệu
    document.addEventListener('input', function(e) {
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
    updateAddressFieldsState(firstAddressDiv, false);
});
</script>