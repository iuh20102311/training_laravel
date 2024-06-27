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
                {{ __('DANH SÁCH SẢN PHẨM') }}
            </h2>
        </x-slot>

        <div class="py-4">
            <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-4">
                            <form action="{{ route('products.index') }}" method="GET" class="form flex items-center space-x-6">
                                <label for="name">Tên <br>
                                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" value="{{ request('name') }}"
                                        class="border rounded px-4 py-2 pr-4">
                                </label>
                                <label for="price">Giá <br>
                                    <input type="number" name="price" placeholder="Nhập giá" value="{{ request('price') }}"
                                        class="border rounded px-4 py-2">
                                </label>
                                <label for="status">Tình trạng <br>
                                    <select name="status" class="border rounded px-4 py-2">
                                        <option value="">Chọn tình trạng</option>
                                        <option value="Đang bán" {{ request('status') == 'Đang bán' ? 'selected' : '' }}>Đang bán</option>
                                        <option value="Hết hàng" {{ request('status') == 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
                                        <option value="Đã ngừng bán" {{ request('status') == 'Đã ngừng bán' ? 'selected' : '' }}>Đã ngừng bán</option>
                                    </select>
                                </label>
                                <button type="submit" class="bg-gray-500 text-white m-4 mx-4 px-2 py-1 rounded">Tìm kiếm</button>
                                <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-4 py-1 rounded">Xóa lọc</a>
                            </form>
                        </div>

                        <a href="{{ route('products.create') }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 inline-block mb-4">+
                            Thêm mới</a>

                        <br>

                        @if ($products->hasPages())
                        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <div class="flex flex-1 justify-between sm:hidden">
                                @if ($products->onFirstPage())
                                    <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500">Previous</span>
                                @else
                                    <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                                @endif

                                @if ($products->hasMorePages())
                                    <a href="{{ $products->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                                @else
                                    <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500">Next</span>
                                @endif
                            </div>
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ $products->firstItem() }}</span>
                                        to
                                        <span class="font-medium">{{ $products->lastItem() }}</span>
                                        of
                                        <span class="font-medium">{{ $products->total() }}</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($products->onFirstPage())
                                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                            @if ($page == $products->currentPage())
                                                <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($products->hasMorePages())
                                            <a href="{{ $products->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                        @endif

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
                                            Tên</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Mô tả</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Gía</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Tình trạng</th>
                                        <th
                                            class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                                            Thao tác</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-100">
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                {{ $product->name }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                {{ Str::limit($product->description, 50) }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                {{ number_format($product->price) }} VND
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                @switch($product->status)
                                                    @case('Đang bán')
                                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">
                                                            Đang bán
                                                        </span>
                                                        @break
                                                    @case('Hết hàng')
                                                        <span class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs">
                                                            Hết hàng
                                                        </span>
                                                        @break
                                                    @case('Đã ngừng bán')
                                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">
                                                            Đã ngừng bán
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs">
                                                            Không xác định
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-2">Sửa</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>