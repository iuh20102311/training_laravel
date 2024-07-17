<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            {{ __('DANH SÁCH ĐƠN HÀNG') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 relative">
                        @include('orders.search-form')
                    </div>

                    <div id="orders-list">
                        @include('orders.pagination-top')
                        @include('orders.table')
                        @include('orders.pagination-bottom')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var clear = false;

        $('#clear-button').click(function (e) {
            e.preventDefault();

            clear = true;
            urlParams.delete('user_name');
            urlParams.delete('order_status');
            urlParams.delete('min_total');
            urlParams.delete('max_total');

            urlParams.set('clear', clear);

            var url = '{{ route('orders.index') }}?' + urlParams.toString();
            $('#search-form input, #search-form select').val('');

            $.ajax({
                url: url,
                type: 'GET',
                data: $('#search-form').serialize(),
                success: function (response) {
                    $('#orders-list').html($(response).find('#orders-list').html());
                },
                error: function (xhr) {
                    console.log('Error:', xhr);
                }
            });
        });

        $(document).on('click', '.update-status', function (e) {
            e.preventDefault();
            var $button = $(this);
            var orderId = $button.data('id');
            var orderStatus = $button.data('status');
            var action = orderStatus == 0 ? 'Xác nhận đơn hàng' : (orderStatus == 1 ? 'Hủy đơn hàng' : 'Khôi phục đơn hàng');
            var $form = $button.closest('form');

            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có chắc chắn muốn ${action}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'PATCH',
                            order_status: orderStatus == 0 ? 1 : (orderStatus == 1 ? 2 : 0)
                        },
                        success: function (response) {
                            var $row = $(`#order-row-${orderId}`);
                            var $statusSpan = $row.find('.order-status-span');
                            var $icon = $button.find('i');

                            if (response.order_status == 0) {
                                $button.removeClass('text-red-500 hover:text-red-700 text-green-500 hover:text-green-700').addClass('text-blue-500 hover:text-blue-700');
                                $icon.removeClass('fa-times fa-undo-alt').addClass('fa-check');
                                $statusSpan.removeClass('bg-green-200 text-green-800 bg-red-200 text-red-800').addClass('bg-gray-200 text-gray-800').text('Đang xử lý');
                                $button.attr('title', 'Xác nhận đơn hàng');
                            } else if (response.order_status == 1) {
                                $button.removeClass('text-blue-500 hover:text-blue-700 text-green-500 hover:text-green-700').addClass('text-red-500 hover:text-red-700');
                                $icon.removeClass('fa-check fa-undo-alt').addClass('fa-times');
                                $statusSpan.removeClass('bg-gray-200 text-gray-800 bg-red-200 text-red-800').addClass('bg-green-200 text-green-800').text('Đã xác nhận');
                                $button.attr('title', 'Hủy đơn hàng');
                            } else if (response.order_status == 2) {
                                $button.removeClass('text-red-500 hover:text-red-700 text-blue-500 hover:text-blue-700').addClass('text-green-500 hover:text-green-700');
                                $icon.removeClass('fa-times fa-check').addClass('fa-undo-alt');
                                $statusSpan.removeClass('bg-green-200 text-green-800 bg-gray-200 text-gray-800').addClass('bg-red-200 text-red-800').text('Đã hủy');
                                $button.attr('title', 'Khôi phục đơn hàng');
                            }

                            $button.data('status', response.order_status);

                            Swal.fire('Thành công!', response.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-order', function (e) {
            e.preventDefault();
            var $button = $(this);
            var orderId = $button.data('id');
            var $form = $button.closest('form');

            Swal.fire({
                title: 'Nhắc nhở',
                text: 'Bạn có chắc chắn muốn xóa đơn hàng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            $(`#order-row-${orderId}`).remove();
                            Swal.fire('Thành công!', response.message, 'success');
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