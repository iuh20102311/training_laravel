<form id="search-form" action="{{ route('users.index') }}" method="GET" class="form">
    {{-- Hàng 1: Các input để lọc dữ liệu --}}
    <div class="flex items-center space-x-6 justify-between">
        {{-- Input lọc theo tên người dùng --}}
        <label for="name" class="flex-1 mx-6">Tên<br>
            <input type="text" name="name" placeholder="Nhập tên người dùng" value="{{ request('name') }}"
                class="w-full border rounded px-4 py-2 pr-4">
        </label>
        {{-- Input lọc theo email --}}
        <label for="email" class="flex-1 mx-6">Email <br>
            <input type="text" name="email" placeholder="Nhập email" value="{{ request('email') }}"
                class="w-full border rounded px-4 py-2">
        </label>
        {{-- Input lọc theo tên nhóm người dùng --}}
        <label for="group_role" class="flex-1 mx-6">Nhóm <br>
            <select name="group_role" class="w-full border rounded py-2">
                <option value="">Chọn nhóm</option>
                <option value="Admin" {{ request('group_role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Editor" {{ request('group_role') === 'Editor' ? 'selected' : '' }}>Editor</option>
                <option value="Reviewer" {{ request('group_role') === 'Reviewer' ? 'selected' : '' }}>Reviewer</option>
            </select>
        </label>
        <label for="is_active" class="flex-1 mx-6">Tình trạng <br>
            <select name="is_active" class="w-full border rounded px-4 py-2">
                <option value="">Chọn tình trạng</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tạm khóa</option>
            </select>
        </label>
    </div>

    {{-- Hàng 2: Nút Thêm mới, Tìm kiếm và Xóa lọc --}}
    <div class="flex items-center justify-between bg-white mt-6 px-4 py-3 sm:px-6">
        {{-- Nút Thêm mới --}}
        <a href="{{ route('users.create') }}"
            class="text-white px-4 py-2 rounded-md transition duration-300 inline-block"
            style=" background-color:#28a745;">
            <i class="fa fa-user-plus"></i> Thêm mới
        </a>

        {{-- Nút Tìm kiếm và Xóa lọc --}}
        <div>
            <button type="submit" style="background-color: #60a5fa;"
                class="text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 inline-flex items-center">
                <i class="fa fa-magnifying-glass" style="margin-right: 10px;"></i> Tìm kiếm
            </button>

            <button type="button" id="clear-button"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300 inline-flex items-center">
                <i class="fa-regular fa-rectangle-xmark" style="margin-right: 10px;"></i> Xóa lọc
            </button>
        </div>
    </div>
</form>
