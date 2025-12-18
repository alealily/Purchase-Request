@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $windowSize = 3; // Show only 3 page numbers
        
        // Calculate start and end of window
        if ($currentPage <= 2) {
            $start = 1;
            $end = min($windowSize, $lastPage);
        } elseif ($currentPage >= $lastPage - 1) {
            $start = max(1, $lastPage - $windowSize + 1);
            $end = $lastPage;
        } else {
            $start = $currentPage - 1;
            $end = $currentPage + 1;
        }
    @endphp
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <ul class="flex items-center gap-1">
            {{-- Previous Page Link (Double Arrow Left = go to previous page) --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="w-9 h-9 flex items-center justify-center text-gray-300 border border-gray-200 rounded cursor-not-allowed">
                        <i class="fa-solid fa-angles-left text-xs"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center text-gray-500 border border-gray-200 rounded hover:bg-gray-50 hover:text-[#187FC4] transition">
                        <i class="fa-solid fa-angles-left text-xs"></i>
                    </a>
                </li>
            @endif

            {{-- Page Numbers (Sliding Window of 3) --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <li>
                        <span class="w-9 h-9 flex items-center justify-center bg-[#187FC4] text-white border border-[#187FC4] rounded font-semibold text-sm">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($page) }}" class="w-9 h-9 flex items-center justify-center text-gray-600 border border-gray-200 rounded hover:bg-gray-50 transition text-sm">
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link (Double Arrow Right = go to next page) --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center text-gray-500 border border-gray-200 rounded hover:bg-gray-50 hover:text-[#187FC4] transition">
                        <i class="fa-solid fa-angles-right text-xs"></i>
                    </a>
                </li>
            @else
                <li>
                    <span class="w-9 h-9 flex items-center justify-center text-gray-300 border border-gray-200 rounded cursor-not-allowed">
                        <i class="fa-solid fa-angles-right text-xs"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
