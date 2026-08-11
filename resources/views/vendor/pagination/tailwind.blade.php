@if ($paginator->hasPages())
<nav class="flex items-center justify-between mt-4">
    <div class="flex-1 flex items-center gap-1">
        @if ($paginator->onFirstPage())
        <span class="px-3 py-2 rounded-lg text-xs font-medium text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
            <i class="bi bi-chevron-left"></i>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">
            <i class="bi bi-chevron-left"></i>
        </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
            <span class="px-3 py-2 rounded-lg text-xs font-medium text-gray-400 bg-gray-100 border border-gray-200">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 rounded-lg text-xs font-bold text-white bg-indigo-600 border border-indigo-600 shadow-sm">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="px-3 py-2 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">
            <i class="bi bi-chevron-right"></i>
        </a>
        @else
        <span class="px-3 py-2 rounded-lg text-xs font-medium text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
            <i class="bi bi-chevron-right"></i>
        </span>
        @endif
    </div>
    <div class="text-xs text-gray-500 ml-4">
        Hal {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
    </div>
</nav>
@endif
