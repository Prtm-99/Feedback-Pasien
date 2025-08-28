@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Tombol Previous --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium 
                             text-white bg-blue-300 cursor-not-allowed rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-4 py-2 text-sm font-medium 
                          text-blue-900 bg-blue-200 border border-blue-300 leading-5 rounded-md 
                          hover:bg-blue-300 hover:text-blue-800 focus:outline-none focus:ring ring-blue-300 
                          active:bg-blue-400 active:text-blue-900 transition ease-in-out duration-150">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium 
                          text-blue-900 bg-blue-200 border border-blue-300 leading-5 rounded-md 
                          hover:bg-blue-300 hover:text-blue-800 focus:outline-none focus:ring ring-blue-300 
                          active:bg-blue-400 active:text-blue-900 transition ease-in-out duration-150">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium 
                             text-white bg-blue-300 cursor-not-allowed rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Pagination Number --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Menampilkan') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('sampai') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('dari') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('hasil') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Tombol Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium 
                                     text-white bg-blue-300 cursor-not-allowed rounded-l-md">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" 
                           class="relative inline-flex items-center px-2 py-2 text-sm font-medium 
                                  text-blue-900 bg-blue-200 border border-blue-300 leading-5 rounded-l-md 
                                  hover:bg-blue-300 hover:text-blue-800 focus:z-10 focus:outline-none focus:ring ring-blue-300 
                                  active:bg-blue-400 active:text-blue-900 transition ease-in-out duration-150">
                            {!! __('pagination.previous') !!}
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium 
                                         text-blue-900 bg-blue-100 border border-blue-300 cursor-default leading-5">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold 
                                                     text-white bg-blue-400 border border-blue-500 rounded-md cursor-default leading-5">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" 
                                       class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium 
                                              text-blue-900 bg-blue-200 border border-blue-300 leading-5 
                                              hover:bg-blue-300 hover:text-blue-800 focus:z-10 focus:outline-none focus:ring ring-blue-300 
                                              active:bg-blue-400 active:text-blue-900 transition ease-in-out duration-150">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" 
                           class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium 
                                  text-blue-900 bg-blue-200 border border-blue-300 leading-5 rounded-r-md 
                                  hover:bg-blue-300 hover:text-blue-800 focus:z-10 focus:outline-none focus:ring ring-blue-300 
                                  active:bg-blue-400 active:text-blue-900 transition ease-in-out duration-150">
                            {!! __('pagination.next') !!}
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium 
                                     text-white bg-blue-300 cursor-not-allowed rounded-r-md">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
