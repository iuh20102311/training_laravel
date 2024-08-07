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
                        @include('users.search-form')
                    </div>

                    <div id="users-list">
                        @include('users.pagination-top')
                        @include('users.table')
                        @include('users.pagination-bottom')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);

        function updateUserList(url) {
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    $('#users-list').html($(response).find('#users-list').html());
                    // Cập nhật phân trang nếu có
                    // $('.pagination-container').html($(response).find('.pagination-container').html());
                    // Cập nhật URL trình duyệt
                    history.pushState(null, '', url);
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                }
            });
        }

        $(document).on('change', '#perPage', function () {
            var urlParams = new URLSearchParams(window.location.search);
            urlParams.set('perPage', this.value);
            var url = '{{ route('users.index') }}?' + urlParams.toString();
            updateUserList(url);
        });

        // Xử lý phân trang Ajax
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            updateUserList(url);
        });

        $('#clear-button').click(function (e) {
            e.preventDefault();
            urlParams.delete('name');
            urlParams.delete('email');
            urlParams.delete('group_role');
            urlParams.delete('is_active');
            urlParams.set('clear', true);

            var url = '{{ route('users.index') }}?' + urlParams.toString();
            $('#search-form input, #search-form select').val('');
            updateUserList(url);
        });
        
        $(document).on('click', '.delete-user', function (e) {
            e.preventDefault();
            var userId = $(this).data('id');
            var userName = $(this).data('name');

            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có muốn xóa thành viên ${userName} không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("users") }}/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#user-row-' + userId).remove();
                            Swal.fire('Đã xóa!', 'Người dùng đã được xóa thành công.', 'success');

                            $('#total-count').text(response.total);

                            // Gửi lại yêu cầu AJAX với tham số tìm kiếm để cập nhật danh sách
                            $.ajax({
                                url: '{{ route('users.index') }}',
                                type: 'GET',
                                data: $('#search-form').serialize(), // Dữ liệu tìm kiếm
                                success: function (response) {
                                    $('#users-list').html($(response).find('#users-list').html()); // Cập nhật danh sách
                                    $('#total-count').text(response.total);
                                },
                                error: function (xhr) {
                                    console.log('Error:', xhr);
                                }
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.toggle-active', function (e) {
            e.preventDefault();
            var $button = $(this);
            var userId = $button.data('id');
            var userName = $button.data('name');
            var isActive = $button.data('active');
            var action = isActive == 1 ? 'tạm khóa' : 'mở khóa';
            var $form = $button.closest('form');

            Swal.fire({
                title: 'Nhắc nhở',
                text: `Bạn có chắc chắn muốn ${action} người dùng "${userName}" này?`,
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
                        data: $form.serialize(),
                        success: function (response) {
                            var $icon = $button.find('i');
                            var $statusSpan = $(`#user-row-${userId} .status-span`);
                            if (response.is_active) {
                                $button.removeClass('text-green-500 hover:text-green-700').addClass('text-yellow-500 hover:text-yellow-700');
                                $icon.removeClass('fa-user-check').addClass('fa-user-times');
                                $statusSpan.removeClass('bg-red-200 text-red-600').addClass('bg-green-200 text-green-600').text('Đang hoạt động');
                                $button.data('active', 1);
                            } else {
                                $button.removeClass('text-yellow-500 hover:text-yellow-700').addClass('text-green-500 hover:text-green-700');
                                $icon.removeClass('fa-user-times').addClass('fa-user-check');
                                $statusSpan.removeClass('bg-green-200 text-green-600').addClass('bg-red-200 text-red-600').text('Tạm khóa');
                                $button.data('active', 0);
                            }
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