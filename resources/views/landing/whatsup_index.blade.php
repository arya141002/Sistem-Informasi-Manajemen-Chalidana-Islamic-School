@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ $title }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-8">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ $title }}</h4>
                        <h1 class="display-4 mb-3">{{ $pageTitle }}</h1>
                        <p class="mb-0">{{ $pageDesc }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('landing.whatsup.index', $section) }}" class="wow fadeInUp" data-wow-delay="0.25s">
                        <label for="q" class="form-label fw-semibold">{{ __('front.search') }}</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="q" name="q" class="form-control" value="{{ $search }}" placeholder="{{ __('front.search_placeholder', ['section' => strtolower($title)]) }}">
                            <button class="btn btn-primary px-4" type="submit">{{ __('front.search_button') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                @forelse($items as $index => $item)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.1), 1) }}s">
                        <article class="blog-item h-100 shadow-sm">
                            <div class="blog-img">
                                <img
                                    src="{{ $item->img ? asset('images/whatsup/' . $item->img) : asset('landing/img/blog-1.png') }}"
                                    class="img-fluid rounded-top w-100 landing-card-image"
                                    alt="{{ localized_value($item, 'title') }}"
                                >
                                <div class="blog-categiry py-2 px-4">
                                    <span>{{ localized_value($item->category, 'title', $title) }}</span>
                                </div>
                            </div>
                            <div class="blog-content p-4 d-flex flex-column">
                                <div class="blog-comment d-flex justify-content-between mb-3 gap-3 flex-wrap">
                                    <div class="small"><span class="fa fa-calendar text-primary"></span> {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}</div>
                                    <div class="small"><span class="fa fa-folder text-primary"></span> {{ localized_value($item->category, 'title', $title) }}</div>
                                </div>
                                <h4 class="mb-3">
                                    <a href="{{ route('landing.whatsup.show', [$section, $item->slug]) }}" class="text-dark">{{ localized_value($item, 'title') }}</a>
                                </h4>
                                <p class="mb-4 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags(localized_value($item, 'content', '')), 130, '...') ?: __('front.whatsup_content_empty') }}</p>
                                <a href="{{ route('landing.whatsup.show', [$section, $item->slug]) }}" class="btn p-0 mt-auto">{{ __('front.read_more') }} <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_whatsup_items', ['section' => strtolower($title)]) }}</div>
                    </div>
                @endforelse
            </div>

            @if($items->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $items->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
