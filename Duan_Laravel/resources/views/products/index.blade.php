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
                    <div class="mb-4 relative">
                    @include('products.search-form')
                    </div>
                
                    {{-- Thanh chuyển trang bên dưới--}}
                    @include('products.pagination-top')

                    {{-- Hiển thị bảng dữ liệu --}}
                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        @if($products->isEmpty())
                            <div class="text-center py-4 text-gray-500">
                                Không có dữ liệu
                            </div>
                        @else
                            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                                <thead>
                                    <tr class="text-left">
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">#</th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Tên</th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Mô tả</th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Giá</th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Tình trạng</th>
                                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-100 {{ $loop->even ? 'bg-red-50' : 'bg-white' }}">
                                            <td class="border-dashed border-t border-red-200 px-6 py-4 text-center">
                                                {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                <span
                                                    onmouseenter="this.imagePreview = showImage(event, '{{ asset('storage/' . $product->image) }}')"
                                                    onmouseleave="hideImage(this.imagePreview)">
                                                    {{ $product->name }}
                                                </span>
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                {{ Str::limit($product->description, 50) }}
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                ${{ $product->price }}
                                                <!-- ${{ number_format($product->price) }} -->
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                @switch($product->status)
                                                    @case('Đang bán')
                                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">
                                                            Đang bán
                                                        </span>
                                                        @break
                                                    @case('Hết hàng')
                                                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">
                                                            Hết hàng
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">
                                                            Ngừng bán
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-500 hover:text-blue-700">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick='return confirm("Bạn có chắc chắn muốn xóa sản phẩm \"{{ $product->name }}\" này?")'>
                                                            <i class="fas fa-trash-alt px-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    {{-- Thanh chuyển trang bên dưới--}}
                    @include('products.pagination-bottom')

                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(event, imageUrl) {
            const imagePreview = document.createElement('img');
            imagePreview.src = imageUrl;
            imagePreview.style.position = 'absolute';
            imagePreview.style.zIndex = '1000';
            imagePreview.style.maxWidth = '200px';
            imagePreview.style.maxHeight = '200px';
            imagePreview.style.border = '1px solid #ccc';
            imagePreview.style.borderRadius = '4px';
            imagePreview.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
            
            document.body.appendChild(imagePreview);
            
            const updateImagePosition = (e) => {
                imagePreview.style.left = `${e.pageX + 10}px`;
                imagePreview.style.top = `${e.pageY + 10}px`;
            };
            
            updateImagePosition(event);
            document.addEventListener('mousemove', updateImagePosition);
            
            return imagePreview;
        }

        function hideImage(imagePreview) {
            document.body.removeChild(imagePreview);
            document.removeEventListener('mousemove', () => {});
        }
    </script>
</x-app-layout>


