@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb bg-breadcrumb-events">
        <div class="container page-title-corner py-5 page-title-container text-start">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.events') }}</h4>
            <ol class="breadcrumb d-flex justify-content-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.events') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-8">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.events') }}</h4>
                        <h1 class="display-4 mb-3">{{ __('front.events_page_title') }}</h1>
                        <p class="mb-0">{{ __('front.events_page_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('landing.events') }}" class="wow fadeInUp" data-wow-delay="0.25s">
                        <label for="study" class="form-label fw-semibold">{{ __('front.events_filter_label') }}</label>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="study" name="study">
                                <option value="">{{ __('front.events_filter_all') }}</option>
                                @foreach($studies as $study)
                                    <option value="{{ $study->id }}" @selected($selectedStudy === $study->id)>{{ $study->title }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary px-4" type="submit">{{ __('front.events_filter_button') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                @forelse($events as $index => $event)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.1), 1) }}s">
                        <article class="blog-item h-100 shadow-sm">
                            <div class="blog-img">
                                <img
                                    src="{{ $event->img ? webp_asset('images/events/' . $event->img) : webp_asset('landing/img/blog-1.png') }}"
                                    class="img-fluid rounded-top w-100 landing-card-image"
                                    alt="{{ localized_value($event, 'title') }}"
                                >
                                <div class="blog-categiry py-2 px-4">
                                    <span>{{ $event->study->title ?? __('front.general') }}</span>
                                </div>
                            </div>
                            <div class="blog-content p-4 d-flex flex-column">
                                <div class="blog-comment d-flex justify-content-between mb-3 gap-3 flex-wrap">
                                    <div class="small"><span class="fa fa-calendar text-primary"></span> {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}</div>
                                    <div class="small"><span class="fa fa-folder text-primary"></span> {{ $event->study->title ?? __('front.general') }}</div>
                                </div>
                                <h4 class="mb-3">
                                    <a href="{{ route('landing.events.show', $event->slug) }}" class="text-dark">{{ localized_value($event, 'title') }}</a>
                                </h4>
                                <p class="mb-4 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags(localized_value($event, 'content', '')), 130, '...') ?: __('front.events_content_empty') }}</p>
                                <a href="{{ route('landing.events.show', $event->slug) }}" class="btn p-0 mt-auto">{{ __('front.read_more') }} <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_events') }}</div>
                    </div>
                @endforelse
            </div>

            @if($events->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $events->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
