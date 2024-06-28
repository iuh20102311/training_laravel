<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <x-app-layout>

        <div class="container mx-auto mt-6 px-4 py-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Thông tin sản phẩm</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap -mx-3">
                        <!-- Left Column -->
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="product-name">
                                    Tên sản phẩm
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="product-name" type="text" placeholder="Nhập tên sản phẩm">
                                <p class="text-red-500 text-xs italic mt-1">Tên sản phẩm không được để trống</p>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                    Giá bán
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="price" type="text" placeholder="Nhập giá bán">
                                <p class="text-red-500 text-xs italic mt-1">Giá bán không được nhỏ hơn 0</p>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                    Trạng thái
                                </label>
                                <select
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="status">
                                    <option>Chọn trạng thái</option>
                                    <!-- Thêm các option khác ở đây -->
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Mô tả
                                </label>
                                <textarea
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="description" rows="4" placeholder="Mô tả sản phẩm"></textarea>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="w-full md:w-1/2 px-3">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Hình ảnh
                                </label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>
                                <div class="flex w-full justify-center">
                                    <button type="button"
                                        class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-bold py-1 px-2 rounded">
                                        Upload
                                    </button>
                                    <button type="button"
                                        class="ml-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-2 rounded">
                                        Xóa file
                                    </button>
                                    <input type="text" placeholder="tên file upload"
                                        class="ml-2 shadow appearance-none border rounded flex-grow py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-6 border-t border-gray-200 flex items-center mb-6 justify-end space-x-6">
                    <button
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                        type="button">
                        Hủy
                    </button>
                    <button
                        class="bg-sky-500 hover:bg-sky-600 text-white mx-6 font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                        type="button">
                        Lưu
                    </button>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>

</html>
