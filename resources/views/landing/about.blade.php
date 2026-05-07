@extends('layouts.front')
@section('landing')
    @php
        $orderedSections = $aboutSections->values();
        $leadSection = $orderedSections->first();
        $bodySections = $orderedSections->slice(1)->values();
    @endphp

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.about_page_title') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.about_us') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid about-editorial py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-xl-3">
                    <aside class="about-editorial-nav wow fadeInUp" data-wow-delay="0.2s">
                        <span class="about-editorial-kicker">{{ __('front.about_school_heading') }}</span>
                        <h2 class="mb-3">{{ __('front.about_page_title') }}</h2>
                        <p class="mb-4">{{ __('front.about_school_intro') }}</p>
                        <div class="about-editorial-links">
                            @forelse($orderedSections as $section)
                                <a href="#about-section-{{ $section->id }}">{{ localized_value($section, 'title', __('front.about_school')) }}</a>
                            @empty
                                <span>{{ __('front.about_ids_missing', ['ids' => '1, 2, 3, 4, 5']) }}</span>
                            @endforelse
                        </div>
                    </aside>
                </div>

                <div class="col-lg-8 col-xl-9">
                    @if($leadSection)
                        <section class="about-hero-panel wow fadeInUp" data-wow-delay="0.2s" id="about-section-{{ $leadSection->id }}">
                            <div class="row g-0 align-items-stretch">
                                <div class="col-xl-6">
                                    <div class="about-hero-copy">
                                        <span class="about-editorial-index">01</span>
                                        <span class="about-editorial-kicker">{{ __('front.about_school_heading') }}</span>
                                        <h1 class="display-5 mb-4">{{ localized_value($leadSection, 'title', __('front.about_school')) }}</h1>
                                        <div class="about-editorial-copy">{!! localized_value($leadSection, 'content', '') !!}</div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="about-hero-media">
                                        @if(!empty($leadSection->img))
                                            <img
                                                src="{{ asset('images/about_school/' . $leadSection->img) }}"
                                                alt="{{ localized_value($leadSection, 'title', __('front.about_school')) }}"
                                            >
                                        @else
                                            <div class="about-hero-placeholder">
                                                <span>{{ localized_value($leadSection, 'title', __('front.about_school')) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    <div class="about-editorial-stack">
                        @forelse($bodySections as $index => $section)
                            @php
                                $isReversed = $index % 2 === 1;
                                $displayNumber = str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT);
                                $sectionImage = !empty($section->img)
                                    ? (in_array($section->id, [5], true)
                                        ? asset('images/' . ltrim($section->img, '/'))
                                        : asset('images/about_school/' . $section->img))
                                    : null;
                            @endphp
                            <section
                                class="about-editorial-block wow fadeInUp"
                                data-wow-delay="{{ number_format(0.3 + ($index * 0.1), 1) }}s"
                                id="about-section-{{ $section->id }}"
                            >
                                <div class="row g-0 align-items-stretch">
                                    <div class="col-xl-5 {{ $isReversed ? 'order-xl-2' : '' }}">
                                        <div class="about-editorial-media {{ $sectionImage ? '' : 'about-editorial-media--empty' }}">
                                            @if($sectionImage)
                                                <img
                                                    src="{{ $sectionImage }}"
                                                    alt="{{ localized_value($section, 'title', __('front.about_school')) }}"
                                                >
                                            @else
                                                <div class="about-editorial-placeholder">
                                                    <span>{{ localized_value($section, 'title', __('front.about_school')) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-7 {{ $isReversed ? 'order-xl-1' : '' }}">
                                        <div class="about-editorial-body">
                                            <span class="about-editorial-index">{{ $displayNumber }}</span>
                                            <h2 class="mb-4">{{ localized_value($section, 'title', __('front.about_school')) }}</h2>
                                            <div class="about-editorial-copy">{!! localized_value($section, 'content', '') !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @empty
                            @if(!$leadSection)
                                <div class="alert alert-light border mb-0 text-center">
                                    {{ __('front.about_ids_missing', ['ids' => '1, 2, 3, 4, 5']) }}
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
