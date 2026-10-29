@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb bg-breadcrumb-achievement">
        <div class="container page-title-corner py-5 page-title-container text-start">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.achievement') }}</h4>
            <ol class="breadcrumb d-flex justify-content-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.achievement') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-8">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.achievement') }}</h4>
                        <h1 class="display-4 mb-3">{{ __('front.achievement_page_title') }}</h1>
                        <p class="mb-0">{{ __('front.achievement_page_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('landing.achievement') }}" class="wow fadeInUp" data-wow-delay="0.25s">
                        <label for="campus" class="form-label fw-semibold">{{ __('front.achievement_filter_label') }}</label>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="campus" name="campus">
                                <option value="">{{ __('front.achievement_filter_all') }}</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" @selected($selectedCampus === $campus->id)>{{ $campus->title }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary px-4" type="submit">{{ __('front.achievement_filter_button') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                @forelse($achievements as $index => $achievement)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.1), 1) }}s">
                        <article class="team-item h-100 shadow-sm border-0">
                            <div class="team-img position-relative">
                                <img
                                    src="{{ $achievement->img ? webp_asset('images/achievement/' . $achievement->img) : webp_asset('landing/img/blog-2.png') }}"
                                    class="img-fluid rounded-top w-100 h-100 landing-card-image"
                                    alt="{{ localized_value($achievement, 'title') }}"
                                >
                            </div>
                            <div class="team-title p-4 h-100">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @if($achievement->campus)
                                        <span class="badge bg-light text-primary">{{ $achievement->campus->title }}</span><br />
                                    @endif
                                    @if($achievement->promoter)
                                        <span class="badge bg-warning text-dark text-wrap text-start">
                                            {{ $achievement->promoter }}
                                        </span>
                                    @endif
                                </div>
                                <p class="small mb-2">
                                    <i class="fa fa-calendar-alt me-2"></i>{{ $achievement->tanggal ? \Carbon\Carbon::parse($achievement->tanggal)->translatedFormat('d M Y') : __('front.events_date_tba') }}
                                </p>
                                <h4 class="mb-3 text-dark">{{ localized_value($achievement, 'title') }}</h4>
                                <div class="mb-0 text-dark" style="text-align: justify;">
                                    {!! localized_value($achievement, 'content', '<p>' . e(__('front.achievement_content_empty')) . '</p>') !!}
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_achievements') }}</div>
                    </div>
                @endforelse
            </div>

            @if($achievements->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $achievements->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
