<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">    

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .niv {
            right: 0;
        }

        .summary {
            right: 0;
        }

        .pagination a, .pagination span {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            color: #007bff;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        .pagination .active span {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .pagination .disabled span {
            color: #6c757d;
            pointer-events: none;
        }

        .pagination .summary {
            margin-left: 20px;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        .pagination-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination-custom a, .pagination-custom span {
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination-custom a:hover {
            background-color: #f5f5f5;
        }

        .pagination-custom .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>

<body class="antialiased">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
                {{ __('DANH SÁCH NGƯỜI DÙNG') }}
            </h2>
        </x-slot>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-4 relative">
                            <form action="{{ route('dashboard') }}" method="GET" class="form">
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
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                                        </select>
                                    </label>
                                </div>

                                {{-- Hàng 2: Nút Thêm mới, Tìm kiếm và Xóa lọc --}}
                                <div class="flex items-center justify-between bg-white mt-6 px-4 py-3 sm:px-6">
                                    {{-- Nút Thêm mới --}}
                                    <a href="{{ route('products.create') }}"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300 inline-block">+
                                        Thêm mới
                                    </a>
                                    {{-- Nút Tìm kiếm và Xóa lọc --}}
                                    <div>
                                        <button type="submit" class="bg-gray-500 text-white px-2 py-1 rounded">Tìm kiếm</button>
                                        <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-1 rounded ml-2">Xóa lọc</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if ($users->hasPages())
                            <div class="flex items-center justify-between bg-white mb-6 px-4 py-3 sm:px-6 relative">
                                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                    <div class="sm:mr-auto">
                                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                            {{-- Link của trang đầu --}}
                                            @if ($users->onFirstPage())
                                                <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Previous</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Previous</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Của của các số trang 1 2 3 .... --}}
                                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                                @if ($page == $users->currentPage())
                                                    <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white bg-red-500 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                                                @else
                                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                                                @endif
                                            @endforeach

                                            {{-- Link trỏ đến trang cuối --}}
                                            @if ($users->hasMorePages())
                                                <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Next</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Next</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif

                                            @if ($users->currentPage() != $users->lastPage())
                                                <a href="{{ $users->url($users->lastPage()) }}" class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Last</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M4.71 14.77a.75.75 0 01-.02-1.06L8.168 10 4.68 6.29a.75.75 0 011.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M10.71 14.77a.75.75 0 01-.02-1.06L14.168 10 10.68 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Last</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.71 14.77a.75.75 0 01-.02-1.06L8.168 10 4.68 6.29a.75.75 0 011.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                        <path fill-rule="evenodd" d="M10.71 14.77a.75.75 0 01-.02-1.06L14.168 10 10.68 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </nav>
                                    </div>

                                    <div class="sm:ml-auto text-sm text-gray-700">
                                        Hiển thị 
                                        <span class="font-medium">{{ $users->firstItem() }}</span>
                                        ~
                                        <span class="font-medium">{{ $users->lastItem() }}</span>
                                        trong tổng số
                                        <span class="font-medium">{{ $users->total() }}</span>
                                        user
                                    </div>
                                </div>
                            </div>
                        @endif

                    
                        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                            @if($users->isEmpty())
                                <div class="text-center py-4 text-gray-500">
                                    Không có dữ liệu
                                </div>
                            @else
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            #
                                        </th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Họ tên
                                        </th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Email
                                        </th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Nhóm
                                        </th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Trạng Thái
                                        </th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($users as $user)
                                        @if($user->is_delete == 0)
                                            <tr class="hover:bg-gray-100">
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
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
                                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Đang hoạt động</span>
                                                    @else
                                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Tạm khóa</span>
                                                    @endif
                                                </td>
                                                <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                    <div class="flex justify-center space-x-2">
                                                        <a href="{{ route('users.edit', $user) }}" class="text-blue-500 hover:text-blue-700">
                                                            <i class="fas fa-pen"></i>
                                                        </a>

                                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                                <i class="fas fa-trash-alt px-2"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('users.delete', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                class="{{ $user->is_active ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }}" 
                                                                onclick="return confirm('{{ $user->is_active ? 'Bạn có chắc chắn muốn tạm khóa người dùng này?' : 'Bạn có chắc chắn muốn mở khóa người dùng này?' }}')">
                                                                <i class="{{ $user->is_active ? 'fas fa-user-times' : 'fas fa-user-check' }}"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <div class="pagination relative">
                            @if ($users->hasPages())
                                <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
                                    <div class="flex justify-between flex-1 sm:hidden">
                                        @if ($users->onFirstPage())
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                                {!! __('pagination.previous') !!}
                                            </span>
                                        @else
                                            <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                {!! __('pagination.previous') !!}
                                            </a>
                                        @endif

                                        @if ($users->hasMorePages())
                                            <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                {!! __('pagination.next') !!}
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                                {!! __('pagination.next') !!}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        
                                        <div>
                                            <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                                {{-- Previous Page Link --}}
                                                @if ($users->onFirstPage())
                                                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                @else
                                                    <a href="{{ $users->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                                    @if ($page == $users->currentPage())
                                                        <span aria-current="page">
                                                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                                        </span>
                                                    @else
                                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                            {{ $page }}
                                                        </a>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($users->hasMorePages())
                                                    <a href="{{ $users->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                                        <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </nav>
                            @endif    

                            @if($users->isNotEmpty())
                                <div class="summary">
                                    Hiển thị từ {{ $users->firstItem() }} ~ {{ $users->lastItem() }} trong tổng số {{ $users->total() }} sản phẩm
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
