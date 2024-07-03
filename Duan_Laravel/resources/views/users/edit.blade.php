<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thông tin người dùng') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="{{ $user->name }}" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="{{ $user->email }}" required>
                        </div>
                        <div>
                            <label for="group_role" class="block text-sm font-medium text-gray-700">Nhóm</label>
                            <select name="group_role" id="group_role" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="Admin" {{ strtolower($user->group_role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Editor" {{ strtolower($user->group_role) == 'editor' ? 'selected' : '' }}>Editor</option>
                                <option value="Reviewer" {{ strtolower($user->group_role) == 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                            </select>
                        </div>
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                            <select name="is_active" id="is_active" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Kích hoạt</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Vô hiệu hóa</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="py-2 px-4 bg-red-600 text-white font-semibold rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Lưu
                            </button>
                            <a href="{{ route('users.index') }}" class="py-2 px-4 bg-gray-200 text-gray-800 font-semibold rounded-md shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

