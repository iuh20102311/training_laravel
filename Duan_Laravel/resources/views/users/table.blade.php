<div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
    @if($users->isEmpty())
        <div class="text-center py-4 text-gray-500">
            Không có dữ liệu
        </div>
    @else
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
            <thead>
                <tr class="text-left">
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        #
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Họ tên
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Email
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Nhóm
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Trạng Thái
                    </th>
                    <th
                        class="bg-red-500 sticky top-0 border-b border-red-600 px-6 py-4 text-white font-bold tracking-wider text-base text-center">
                        Thao tác
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr class="hover:bg-gray-100 {{ $loop->even ? 'bg-red-50' : 'bg-white' }}" id="user-row-{{ $user->id }}">
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
                                <span class="status-span bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Đang hoạt
                                    động</span>
                            @else
                                <span class="status-span bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Tạm khóa</span>
                            @endif
                        </td>

                        <td class="border-dashed border-t border-gray-200 px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <button type="button" class="text-red-500 hover:text-red-700 delete-user"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <i class="fas fa-trash-alt px-2"></i>
                                </button>

                                <form action="{{ route('users.active', $user) }}" method="POST"
                                    class="inline toggle-active-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                        class="toggle-active {{ $user->is_active ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }}"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        data-active="{{ $user->is_active }}">
                                        <i class="{{ $user->is_active ? 'fas fa-user-times' : 'fas fa-user-check' }}"></i>
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