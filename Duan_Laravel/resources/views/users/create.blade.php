<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Tạo mới người dùng') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700">Tên</label>
                            <input type="text" name="name" id="name"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700">Mật khẩu</label>
                            <input type="password" name="password" id="password"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            @error('password')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700">Xác nhận
                                mật khẩu</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            @error('password_confirmation')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="group_role" class="block text-sm font-bold text-gray-700">Nhóm</label>
                            <select name="group_role" id="group_role"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="Admin" {{ old('group_role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Editor" {{ old('group_role') == 'Editor' ? 'selected' : '' }}>Editor
                                </option>
                                <option value="Reviewer" {{ old('group_role') == 'Reviewer' ? 'selected' : '' }}>Reviewer
                                </option>
                            </select>
                            @error('group_role')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="is_active" class="block text-sm font-bold text-gray-700">Trạng thái</label>
                            <select name="is_active" id="is_active"
                                class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-4 my-4">
                            <a href="{{ route('users.index') }}"
                                class="py-2 px-6 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Hủy
                            </a>

                            <button type="submit"
                                class="py-2 px-6 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>