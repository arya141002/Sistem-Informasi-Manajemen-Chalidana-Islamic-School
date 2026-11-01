@extends('layouts.front')

@section('landing')
    @php
        $breadcrumbImageUrl = !empty($item->img)
            ? webp_asset('images/whatsup/' . $item->img)
            : null;
    @endphp

    <div class="container-fluid bg-breadcrumb {{ $breadcrumbImageUrl ? 'bg-breadcrumb-dynamic' : '' }}">
        @if($breadcrumbImageUrl)
            <img src="{{ $breadcrumbImageUrl }}" alt="" class="breadcrumb-bg-image" aria-hidden="true">
        @endif
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ $title }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('landing.whatsup.index', $section) }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ localized_value($item, 'title') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="bg-white rounded-4 shadow-sm overflow-hidden wow fadeInUp" data-wow-delay="0.2s">
                        <img
                            src="{{ $item->img ? webp_asset('images/whatsup/' . $item->img) : webp_asset('landing/img/blog-1.png') }}"
                            alt="{{ localized_value($item, 'title') }}"
                            class="w-100 detail-hero-image"
                        >
                        <div class="p-4 p-lg-5">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ localized_value($item->category, 'title', $title) }}</span>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="fa fa-calendar-alt me-2"></i>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}
                                </span>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="fa fa-eye me-2"></i>{{ __('front.views_count', ['count' => $item->views_count]) }}
                                </span>
                            </div>
                            <h1 class="display-6 mb-4">{{ localized_value($item, 'title') }}</h1>
                            <div class="text-muted event-detail-content">
                                {!! localized_value($item, 'content', '<p>' . e(__('front.whatsup_content_empty')) . '</p>') !!}
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 wow fadeInUp" data-wow-delay="0.25s">
                        <h4 class="mb-3">{{ $title }}</h4>
                        <p class="text-muted mb-4">{{ $pageDesc }}</p>
                        <a href="{{ route('landing.whatsup.index', $section) }}" class="btn btn-outline-primary rounded-pill px-4 py-2">{{ __('front.back_to_section', ['section' => $title]) }}</a>
                    </div>

                    <div class="bg-white rounded-4 shadow-sm p-4 mt-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h4 class="mb-4">{{ __('front.related_items') }}</h4>
                        <div class="d-flex flex-column gap-4">
                            @forelse($relatedItems as $relatedItem)
                                <article>
                                    <div class="small text-primary mb-2">{{ $relatedItem->tanggal ? \Carbon\Carbon::parse($relatedItem->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}</div>
                                    <h5 class="mb-2">
                                        <a href="{{ route('landing.whatsup.show', [$section, $relatedItem->slug]) }}" class="text-dark">
                                            {{ localized_value($relatedItem, 'title') }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit(strip_tags(localized_value($relatedItem, 'content', '')), 90, '...') ?: __('front.whatsup_content_empty') }}</p>
                                    <a href="{{ route('landing.whatsup.show', [$section, $relatedItem->slug]) }}" class="btn p-0">{{ __('front.read_more') }} <i class="fa fa-arrow-right"></i></a>
                                </article>
                            @empty
                                <p class="text-muted mb-0">{{ __('front.no_related_items') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
