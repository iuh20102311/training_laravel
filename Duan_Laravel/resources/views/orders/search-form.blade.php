<form id="search-form" action="{{ route('orders.index') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="name" class="block text-gray-700 font-bold mb-2">Tên khách hàng</label>
            <input type="text" name="user_name" placeholder="Nhập tên khách hàng" value="{{ request('name') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="order_status" class="block text-gray-700 font-bold mb-2">Trạng thái</label>
            <select name="order_status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn tình trạng</option>
                <option value="0" {{ request('order_status') === 0 ? 'selected' : '' }}>Đang xử lý</option>
                <option value="1" {{ request('order_status') ===  1 ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="2" {{ request('order_status') === 2 ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        <div>
            <label for="min_total" class="block text-gray-700 font-bold mb-2">Tổng tiền từ</label>
            <input type="number" name="min_total" value="{{ request('min_total') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
        </div>
        <div>
            <label for="max_total" class="block text-gray-700 font-bold mb-2">Tổng tiền đến</label>
            <input type="number" name="max_total" value="{{ request('max_total') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <!-- <a href="{{ route('orders.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fa fa-user-plus mr-2"></i> Thêm mới
        </a> -->
        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fa fa-magnifying-glass mr-2"></i> Tìm kiếm
            </button>
            <button type="button" id="clear-button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2 inline-flex items-center">
                <i class="fa-regular fa-rectangle-xmark mr-2"></i> Xóa lọc
            </button>
        </div>
    </div>
</form>
