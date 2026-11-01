@if ($paginator->hasPages())
    <style>
        .front-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.6rem;
            max-width: 100%;
        }

        .front-pagination .page-item {
            list-style: none;
        }

        .front-pagination .page-link {
            min-width: 46px;
            height: 46px;
            padding: 0 1rem;
            border: 1px solid rgba(43, 78, 161, 0.16);
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #1f418f;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            transition: all 0.2s ease;
        }

        .front-pagination .page-link:hover {
            background: #f3f7ff;
            border-color: rgba(43, 78, 161, 0.3);
            color: #16306b;
            transform: translateY(-1px);
        }

        .front-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2b4ea1 0%, #1f418f 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 14px 30px rgba(31, 65, 143, 0.28);
        }

        .front-pagination .page-item.disabled .page-link {
            background: #f8f9fc;
            color: #94a3b8;
            border-color: rgba(148, 163, 184, 0.2);
            box-shadow: none;
            pointer-events: none;
        }

        .front-pagination .page-item.page-item-nav .page-link {
            padding: 0 1.2rem;
        }

        @media (max-width: 576px) {
            .front-pagination {
                justify-content: flex-start;
                flex-wrap: nowrap;
                gap: 0.45rem;
                overflow-x: auto;
                padding: 0.25rem 0.15rem 0.7rem;
                scrollbar-width: thin;
            }

            .front-pagination .page-link {
                flex: 0 0 auto;
                min-width: 42px;
                height: 42px;
                padding: 0 0.9rem;
                font-size: 0.92rem;
            }

            .front-pagination .page-item.page-item-nav .page-link {
                padding: 0 1rem;
            }
        }
    </style>

    <nav aria-label="Pagination Navigation">
        <ul class="front-pagination mb-0 ps-0">
            @if ($paginator->onFirstPage())
                <li class="page-item page-item-nav disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item page-item-nav">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $firstVisiblePage = max(1, min($currentPage - 1, $lastPage - 2));
                $lastVisiblePage = min($lastPage, $firstVisiblePage + 2);
            @endphp

            @for ($page = $firstVisiblePage; $page <= $lastVisiblePage; $page++)
                @if ($page === $currentPage)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor

            @if ($paginator->hasMorePages())
                <li class="page-item page-item-nav">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item page-item-nav disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
