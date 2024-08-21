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

                    <div id="products-list">
                        @include('products.pagination-top')
                        @include('products.table')
                        @include('products.pagination-bottom')
                    </div>
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
            document.removeEventListener('mousemove', () => { });
        }
    </script>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Đặt hàng thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif
    });
    
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var clear = false;

        $('#clear-button').click(function (e) {
            e.preventDefault();

            clear = true;
            urlParams.delete('name');
            urlParams.delete('status');
            urlParams.delete('min_price');
            urlParams.delete('max_price');

            urlParams.set('clear', clear);

            var url = '{{ route('products.index') }}?' + urlParams.toString();
            $('#search-form input, #search-form select').val('');

            $.ajax({
                url: url,
                type: 'GET',
                data: $('#search-form').serialize(),
                success: function (response) {
                    $('#products-list').html($(response).find('#products-list').html());
                },
                error: function (xhr) {
                    console.log('Error:', xhr);
                }
            });
        });

        $(document).on('change', '#perPage', function () {
            var perPage = this.value;

            urlParams.delete('clear');
            urlParams.set('perPage', perPage);

            var url = '{{ route('products.index') }}?' + urlParams.toString();

            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    $('#products-list').html($(response).find('#products-list').html());
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $(document).on('click', '.delete-product', function (e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productName = $(this).data('name');

            var currentFilters = {
                name: $('#search-input').val(),
                status: $('#status-filter').val(),
                min_price: $('#min-price-filter').val(),
                max_price: $('#max-price-filter').val()
            };

            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có muốn xóa sản phẩm ${productName} không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("products") }}/' + productId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: currentFilters.name,
                            status: currentFilters.status,
                            min_price: currentFilters.min_price,
                            max_price: currentFilters.max_price
                        },
                        success: function (response) {
                            $('#product-row-' + productId).remove();
                            Swal.fire('Đã xóa!', 'Sản phẩm đã được xóa thành công.', 'success');
                            $('#total-count').text(response.total);
                        },
                        error: function (xhr) {
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>