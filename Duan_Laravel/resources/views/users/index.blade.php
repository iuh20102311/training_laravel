<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
                {{ __('DANH SÁCH NGƯỜI DÙNG') }}
            </h2>
        </x-slot>

        <div class="py-4">
            <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-4">
                            <form action="{{ route('dashboard') }}" method="GET" class="form flex items-center space-x-6">
                                <label for="name">Tên <br>
                                    <input type="text" name="name" placeholder="Nhập tên người dùng" value="{{ request('name') }}"
                                        class="border rounded px-4 py-2 pr-4">
                                </label>
                                <label for="price">Email <br>
                                    <input type="number" name="price" placeholder="Nhập giá" value="{{ request('email') }}"
                                        class="border rounded px-4 py-2">
                                </label>
                                <label for="status">Tình trạng <br>
                                    <select name="status" class="border rounded px-4 py-2">
                                        <option value="" selected>Chọn tình trạng</option>
                                        <option value="1" {{ request('is_active') == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="0" {{ request('is_active') == 0 ? 'selected' : '' }}>Tạm khóa</option>
                                    </select>
                                </label>
                                <button type="submit" class="bg-gray-500 text-white m-4 mx-4 px-2 py-1 rounded">Tìm kiếm</button>
                                <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-1 rounded">Xóa lọc</a>
                            </form>
                        </div>

                        <a href="{{ route('users.create') }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 inline-block mb-4">+
                            Thêm mới</a>

                        <br>
                        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                            <table
                                class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            #</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Họ tên</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Email</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Nhóm</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Trạng Thái</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Thao tác</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        @if($user->is_delete == 0)
                                            <tr class="hover:bg-gray-100">
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    {{ $user->group_role }}
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    @if($user->is_active == 1)
                                                        <span
                                                            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Đang
                                                            hoạt động</span>
                                                    @else
                                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Tạm
                                                            khóa</span>
                                                    @endif
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-2">Sửa</a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>