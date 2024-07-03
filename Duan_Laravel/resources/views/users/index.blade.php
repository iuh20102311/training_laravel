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
                        {{-- Thanh tìm kiếm, xóa tìm kiếm, thêm người dùng mới--}}
                        @include('users.search-form')
                    </div>

                    {{-- Thanh chuyển trang bên trên--}}
                    @include('users.pagination-top')
                    {{--- Modal thêm người người dùng --}}
                    @if(request()->has('showModal'))
                        @include('users.create-modal')
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
                                        <tr class="hover:bg-gray-100 {{ $loop->even ? 'bg-red-50' : 'bg-white' }}">
                                            <td class="border-dashed border-t border-red-200 px-6 py-4 text-center">
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
                                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick='return confirm("Bạn có chắc chắn muốn xóa người dùng \"{{ $user->name }}\" này?")'>
                                                            <i class="fas fa-trash-alt px-2"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('users.active', $user) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                            class="{{ $user->is_active ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }}" 
                                                            onclick="return confirm('{{ $user->is_active ? 'Bạn có chắc chắn muốn tạm khóa người dùng \"' . addslashes($user->name) . '\" này?' : 'Bạn có chắc chắn muốn mở khóa người dùng \"' . addslashes($user->name) . '\" này?' }}')">
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

                    {{-- Thanh chuyển trang bên dưới--}}
                    @include('users.pagination-bottom')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>