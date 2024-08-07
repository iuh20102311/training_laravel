<!-- Form Import nằm ngoài form tìm kiếm -->
<form id="import-form" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data"
    style="display: none;">
    @csrf
    <input type="file" name="file" accept=".csv">
</form>

<form id="search-form" action="{{ route('users.index') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="name" class="block text-gray-700 font-bold mb-2">Tên</label>
            <input type="text" name="name" placeholder="Nhập tên người dùng" value="{{ request('name') }}"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="text" name="email" placeholder="Nhập email" value="{{ request('email') }}"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="group_role" class="block text-gray-700 font-bold mb-2">Nhóm</label>
            <select name="group_role"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn nhóm</option>
                <option value="Admin" {{ request('group_role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Editor" {{ request('group_role') === 'Editor' ? 'selected' : '' }}>Editor</option>
                <option value="Reviewer" {{ request('group_role') === 'Reviewer' ? 'selected' : '' }}>Reviewer</option>
            </select>
        </div>
        <div>
            <label for="is_active" class="block text-gray-700 font-bold mb-2">Tình trạng</label>
            <select name="is_active"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn tình trạng</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tạm khóa</option>
            </select>
        </div>
    </div>

    <div class="flex justify-between mt-4">
        <div class="flex items-center space-x-2">
            <a href="{{ route('users.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fa fa-user-plus mr-2"></i> Thêm mới
            </a>

            <!-- Label cho Import CSV -->
            <div class="inline">
                <label for="file-upload"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center cursor-pointer">
                    <i class="fa fa-file-import mr-2"></i> Import CSV
                    <input id="file-upload" type="file" accept=".csv" class="hidden">
                </label>
            </div>

            <a href="{{ route('users.export', request()->query()) }}"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fa fa-file-export mr-2"></i> Export CSV
            </a>
        </div>

        <div>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fa fa-magnifying-glass mr-2"></i> Tìm kiếm
            </button>
            <button type="button" id="clear-button"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ml-2 inline-flex items-center">
                <i class="fa-regular fa-rectangle-xmark mr-2"></i> Xóa lọc
            </button>
        </div>
    </div>
</form>

<script>
    document.getElementById('file-upload').addEventListener('change', function () {
        if (this.files.length > 0) {
            let importForm = document.getElementById('import-form');
            importForm.querySelector('input[type="file"]').files = this.files;
            importForm.submit();
        }
    });
</script>