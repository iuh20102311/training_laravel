
<x-app-layout>
    <div class="container mx-auto mt-6 px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Thêm sản phẩm mới</h2>
            </div>
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="p-6">
                    <div class="flex flex-wrap -mx-3">
                        <!-- Left Column -->
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="product-name">
                                    Tên sản phẩm
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                    id="product-name" name="name" type="text" placeholder="Nhập tên sản phẩm">
                                @error('name')
                                <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                    Giá bán
                                </label>
                                <input
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror"
                                    id="price" name="price" type="text" placeholder="Nhập giá bán">
                                @error('price')
                                <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                    Trạng thái
                                </label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="Đang bán">Đang bán</option>
                                    <option value="Ngừng bán">Ngừng bán</option>
                                    <option value="Hết hàng">Hết hàng</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Mô tả
                                </label>
                                <textarea
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Mô tả sản phẩm"></textarea>
                                @error('description')
                                <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="w-full md:w-1/2 px-3">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Hình ảnh
                                </label>

                                <div class="mt-6 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                    <div class="space-y-4">
                                        <div id="imageContainer" class="rounded-lg p-4 flex justify-center items-center" style="height: 200px;">
                                            <svg id="defaultImage" class="h-32 w-32 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <img id="preview" src="#" alt="Preview" class="max-w-full max-h-full object-contain" style="display: none;">
                                        </div>
                                        <div class="flex items-center space-x-2 mt-6">
                                            <input type="file" name="image" id="image" class="hidden" accept="image/*">
                                            <button type="button" id="uploadButton" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" style="background-color:green; margin-right: 10px;">
                                                Upload
                                            </button>
                                            <button type="button" id="deleteButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" style="margin-right: 10px;">
                                                Xóa file
                                            </button>
                                            <span id="fileName" class="text-sm text-gray-600 flex-grow border border-gray-300"></span>
                                        </div>
                                        @error('image')
                                        <p class="text-red-500 text-xs italic" style="color:red">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 border-t border-gray-200 flex items-center mb-6 justify-end space-x-6">
                    <a href="{{ route('products.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" style="margin-right:10px">
                        Hủy
                    </a>
                    <button
                        class="bg-sky-500 hover:bg-sky-600 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline"
                        type="submit" style="background-color:green">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadButton = document.getElementById('uploadButton');
        const deleteButton = document.getElementById('deleteButton');
        const imageInput = document.getElementById('image');
        const defaultImage = document.getElementById('defaultImage');
        const preview = document.getElementById('preview');
        const nameInput = document.getElementById('product-name');
        const fileNameSpan = document.getElementById('fileName');

        uploadButton.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    defaultImage.style.display = 'none';
                }
                reader.readAsDataURL(file);

                // Tạo tên file mới
                const productName = nameInput.value.trim();
                const firstChar = productName.charAt(0).toUpperCase();
                const randomNum = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
                const newFileName = `${firstChar}${randomNum}.${file.name.split('.').pop()}`;

                // Hiển thị tên file mới
                fileNameSpan.textContent = newFileName;

                // Tạo một đối tượng File mới với tên file mới
                const newFile = new File([file], newFileName, {type: file.type});

                // Tạo một đối tượng DataTransfer mới và thêm file mới vào
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);

                // Gán files mới cho input
                imageInput.files = dataTransfer.files;
            }
        });

        deleteButton.addEventListener('click', function() {
            imageInput.value = '';
            preview.src = '#';
            preview.style.display = 'none';
            defaultImage.style.display = 'block';
            fileNameSpan.textContent = '';
        });
    });
</script>

