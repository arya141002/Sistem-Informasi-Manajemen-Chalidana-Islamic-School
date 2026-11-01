@extends('layouts.front')

@section('landing')
    @php
        $breadcrumbImageUrl = !empty($event->img)
            ? webp_asset('images/events/' . $event->img)
            : null;
    @endphp

    <div class="container-fluid bg-breadcrumb {{ $breadcrumbImageUrl ? 'bg-breadcrumb-dynamic' : '' }}">
        @if($breadcrumbImageUrl)
            <img src="{{ $breadcrumbImageUrl }}" alt="" class="breadcrumb-bg-image" aria-hidden="true">
        @endif
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.events') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('landing.events') }}">{{ __('front.events') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ localized_value($event, 'title') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-8">
                    <article class="bg-white rounded-4 shadow-sm overflow-hidden wow fadeInUp" data-wow-delay="0.2s">
                        <img
                            src="{{ $event->img ? webp_asset('images/events/' . $event->img) : webp_asset('landing/img/blog-1.png') }}"
                            alt="{{ localized_value($event, 'title') }}"
                            class="w-100 detail-hero-image"
                        >
                        <div class="p-4 p-lg-5">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $event->study->title ?? __('front.general') }}</span>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="fa fa-calendar-alt me-2"></i>{{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}
                                </span>
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="fa fa-eye me-2"></i>{{ __('front.views_count', ['count' => $event->views_count]) }}
                                </span>
                            </div>
                            <h1 class="display-6 mb-4">{{ localized_value($event, 'title') }}</h1>
                            <div class="text-muted event-detail-content">
                                {!! localized_value($event, 'content', '<p>' . e(__('front.events_content_empty')) . '</p>') !!}
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 wow fadeInUp" data-wow-delay="0.25s">
                        <h4 class="mb-3">{{ __('front.events_sidebar_title') }}</h4>
                        <p class="text-muted mb-4">{{ __('front.events_sidebar_desc') }}</p>
                        <a href="{{ route('landing.events') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">{{ __('front.back_to_events') }}</a>
                    </div>

                    <div class="bg-white rounded-4 shadow-sm p-4 mt-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h4 class="mb-4">{{ __('front.related_events') }}</h4>
                        <div class="d-flex flex-column gap-4">
                            @forelse($relatedEvents as $relatedEvent)
                                <article>
                                    <div class="small text-primary mb-2">{{ $relatedEvent->tanggal ? \Carbon\Carbon::parse($relatedEvent->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}</div>
                                    <h5 class="mb-2">
                                        <a href="{{ route('landing.events.show', $relatedEvent->slug) }}" class="text-dark">
                                            {{ localized_value($relatedEvent, 'title') }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit(strip_tags(localized_value($relatedEvent, 'content', '')), 90, '...') ?: __('front.events_content_empty') }}</p>
                                    <a href="{{ route('landing.events.show', $relatedEvent->slug) }}" class="btn p-0">{{ __('front.read_more') }} <i class="fa fa-arrow-right"></i></a>
                                </article>
                            @empty
                                <p class="text-muted mb-0">{{ __('front.no_related_events') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
