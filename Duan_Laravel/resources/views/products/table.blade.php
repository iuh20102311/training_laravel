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
                    @if(in_array(Auth::user()->group_role, ['Admin', 'Editor', 'Reviewer']))
                        <th class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">Thao tác</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="hover:bg-gray-100 {{ $loop->even ? 'bg-red-50' : 'bg-white' }}" id="product-row-{{ $product->product_id }}">
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

                        @if(in_array(Auth::user()->group_role, ['Admin', 'Editor']))
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button type="button" class="text-red-500 hover:text-red-700 delete-product"
                                            data-id="{{ $product->product_id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-trash-alt px-2"></i>
                                    </button>
                                </div>
                            </td>
                        @elseif(in_array(Auth::user()->group_role, ['Reviewer']))
                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button type="button" class="text-green-500 hover:text-green-700 add-to-order"
                                            data-id="{{ $product->product_id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>