<form id="search-form" action="{{ route('products.index') }}" method="GET" class="form">
    {{-- Hàng 1: Các input để lọc dữ liệu --}}
    <div class="flex items-center space-x-6 justify-between">
        {{-- Input lọc theo tên sản phẩm --}}
        <label for="name" class="flex-1 mx-6">Tên sản phẩm
            <input type="text" name="name" placeholder="Nhập tên sản phẩm" value="{{ request('name') }}"
                class="w-full border rounded px-4 py-2 pr-4">
        </label>
        {{-- Input lọc theo trạng thái --}}
        <label for="status" class="flex-1 mx-6">Trạng thái
            <select name="status" class="w-full border rounded py-2">
                <option value="">Chọn tình trạng</option>
                <option value="Đang bán" {{ request('status') == 'Đang bán' ? 'selected' : '' }}>Đang bán</option>
                <option value="Hết hàng" {{ request('status') == 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
                <option value="Ngừng bán" {{ request('status') == 'Ngừng bán' ? 'selected' : '' }}>Ngừng bán</option>
            </select>
        </label>
        {{-- Input lọc theo giá bán từ đến --}}
        <label for="min_price" class="flex-1 mx-6">Giá bán từ
            <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full border rounded py-2" min="0">
        </label>
    
        <span class="nig">~</span>

        <label for="max_price" class="flex-1 mx-6">Giá bán đến
            <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full border rounded py-2" min="0">
        </label>
    </div>

    {{-- Hàng 2: Nút Thêm mới, Tìm kiếm và Xóa lọc --}}
    <div class="flex items-center justify-between bg-white mt-6 px-4 py-3 sm:px-6">
        {{-- Nút Thêm mới --}}
        <a href="{{ route('products.create') }}"
            class="text-white px-4 py-2 rounded-md transition duration-300 inline-block" style=" background-color:#28a745;">
            <i class="fa fa-user-plus"></i> Thêm mới
        </a>

        {{-- Nút Tìm kiếm và Xóa lọc --}}
        <div>
            <button type="submit" style="background-color: #60a5fa;" class="text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 inline-flex items-center">
                <i class="fa fa-magnifying-glass" style="margin-right: 10px;"></i> Tìm kiếm
            </button>

            <button type="button" id="clear-button"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300 inline-flex items-center">
                <i class="fa-regular fa-rectangle-xmark" style="margin-right: 10px;"></i> Xóa lọc
            </button>
        </div>
    </div>
</form>
