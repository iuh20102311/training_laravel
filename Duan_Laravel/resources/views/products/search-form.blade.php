<form id="search-form" action="{{ route('products.index') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="name" class="block text-gray-700 font-bold mb-2">Tên sản phẩm</label>
            <input type="text" name="name" placeholder="Nhập tên sản phẩm" value="{{ request('name') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="status" class="block text-gray-700 font-bold mb-2">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn tình trạng</option>
                <option value="Đang bán" {{ request('status') == 'Đang bán' ? 'selected' : '' }}>Đang bán</option>
                <option value="Hết hàng" {{ request('status') == 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
                <option value="Ngừng bán" {{ request('status') == 'Ngừng bán' ? 'selected' : '' }}>Ngừng bán</option>
            </select>
        </div>
        <div>
            <label for="min_price" class="block text-gray-700 font-bold mb-2">Giá bán từ</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
        </div>
        <div>
            <label for="max_price" class="block text-gray-700 font-bold mb-2">Giá bán đến</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
        </div>
    </div>

    @if(in_array(Auth::user()->group_role, ['Admin', 'Editor']))
        <div class="flex justify-between mt-4">
            <a href="{{ route('products.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fa fa-user-plus mr-2"></i> Thêm mới
            </a>
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fa fa-magnifying-glass mr-2"></i> Tìm kiếm
                </button>
                <button type="button" id="clear-button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2 inline-flex items-center">
                    <i class="fa-regular fa-rectangle-xmark mr-2"></i> Xóa lọc
                </button>
            </div>
        </div>
    @elseif(in_array(Auth::user()->group_role, ['Reviewer']))
        <div class="flex justify-end mt-4">
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fa fa-magnifying-glass mr-2"></i> Tìm kiếm
                </button>
                <button type="button" id="clear-button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2 inline-flex items-center">
                    <i class="fa-regular fa-rectangle-xmark mr-2"></i> Xóa lọc
                </button>
            </div>
        </div>
    @endif
</form>
