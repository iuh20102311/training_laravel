@if ($orders->hasPages())
    <div class="w-full flex flex-col sm:flex-row items-center sm:justify-between my-6">
        <div class="hidden sm:block sm:w-1/4"></div> <!-- Ẩn trên mobile -->
        <div class="w-full sm:w-1/2 flex justify-center sm:mb-0">
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                {{-- Link của trang đầu --}}
                @if ($orders->onFirstPage())
                    <span
                        class="inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}"
                        class="inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Các số trang --}}
                @php
                    $start = max($orders->currentPage() - 2, 1);
                    $end = min($start + 3, $orders->lastPage());
                    $start = max($end - 4, 1);
                @endphp

                @if($start > 1)
                    <a href="{{ $orders->url(1) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</a>
                    @if($start > 2)
                        <span
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                    @endif
                @endif

                @foreach (range($start, $end) as $page)
                    @if ($page == $orders->currentPage())
                        <span aria-current="page"
                            class="z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white bg-red-500 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                    @else
                        <a href="{{ $orders->appends(request()->except('page'))->url($page) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                    @endif
                @endforeach

                @if($end < $orders->lastPage())
                    @if($end < $orders->lastPage() - 1)
                        <span
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                    @endif
                    <a href="{{ $orders->url($orders->lastPage()) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $orders->lastPage() }}</a>
                @endif

                {{-- Link trỏ đến trang tiếp theo --}}
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"
                        class="inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span
                        class="inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif

                {{-- Link đến trang cuối cùng --}}
                @if ($orders->currentPage() != $orders->lastPage())
                    <a href="{{ $orders->url($orders->lastPage()) }}"
                        class="inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Last</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M4.71 14.77a.75.75 0 01-.02-1.06L8.168 10 4.68 6.29a.75.75 0 011.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M10.71 14.77a.75.75 0 01-.02-1.06L14.168 10 10.68 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span
                        class="inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Last</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M4.71 14.77a.75.75 0 01-.02-1.06L8.168 10 4.68 6.29a.75.75 0 011.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M10.71 14.77a.75.75 0 01-.02-1.06L14.168 10 10.68 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </nav>
        </div>

        <div class="w-full sm:w-1/4 text-sm text-gray-700 text-right whitespace-nowrap mb-4 sm:mb-0">
            Hiển thị
            <span class="font-medium"><b id="first-item">{{ $orders->firstItem() }}</b></span>
            ~
            <span class="font-medium"><b id="last-item">{{ $orders->lastItem() }}</b></span>
            trong tổng số
            <span class="font-medium"><b id="total-count">{{ $orders->total() }}</b></span>
            đơn hàng
        </div>
    </div>
@endif