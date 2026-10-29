@extends('layouts.front')
@section('landing')
    <div class="container-fluid hero-section py-5">
        <!-- Video Background Terkompresi -->
        <video class="hero-background-video" autoplay muted loop playsinline preload="none" aria-hidden="true">
            <source src="{{ asset('video/0715.webm') }}" type="video/webm">
        </video>
        <div class="container py-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8 col-xl-7 animated fadeInLeft">
                    <div class="text-sm-center text-md-start">
                        <h1 class="display-5 display-md-3 text-white text-lg-nowrap mb-3 [&_p]:d-inline [&_br]:d-none">
                            {!! safe_rich_text(localized_value($tagline, 'title', $settings['site_name'] ?? config('app.name'))) !!}
                        </h1>
                        <div class="mb-4 fs-5 hero-tagline-content">{!! safe_rich_text(localized_value($tagline, 'jargon', $settings['site_description'] ?? ('Website resmi ' . config('app.name')))) !!}</div>
                        <div class="mb-4 fs-5 hero-tagline-content">{!! safe_rich_text(localized_value($tagline, 'content', $settings['site_description'] ?? ('Website resmi ' . config('app.name')))) !!}</div>
                        <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                            <button type="button" class="btn btn-light rounded-pill py-3 px-4 px-md-5 me-2" data-bs-toggle="modal" data-bs-target="#homeVideoModal"><i class="fas fa-play-circle me-2"></i> {{ __('front.watch_video') }}</button>
                            <a class="btn btn-dark rounded-pill py-3 px-4 px-md-5 ms-2" href="{{ url('/about-us') }}">{{ __('front.learn_more') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Video (Dioptimasi tanpa dual preload) -->
    <div class="modal fade" id="homeVideoModal" tabindex="-1" aria-labelledby="homeVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="homeVideoModalLabel">{{ __('front.watch_video') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <video id="homeVideoModalPlayer" class="w-100 h-100" controls playsinline preload="none">
                            <source data-src="{{ asset('video/0715.webm') }}" type="video/webm">
                            {{ __('front.your_browser_does_not_support_video') }}
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Start -->
    <div class="container-fluid bg-light about py-5 hex-silhouette-section">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="cis-story-card h-100" id="cisUpdateStories" data-story-duration="4500">
                        @if(($cisUpdates ?? collect())->isNotEmpty())
                            <div class="cis-story-track">
                                @foreach($cisUpdates as $index => $cisUpdate)
                                    <article
                                        class="cis-story-slide {{ $index == 0 ? 'is-active' : '' }}"
                                        data-story-slide
                                        data-index="{{ $index }}"
                                    >
                                        <div class="cis-story-progress">
                                            @foreach($cisUpdates as $progressIndex => $progressItem)
                                                <div class="cis-story-progress-item">
                                                    <div
                                                        class="cis-story-progress-bar {{ $index === $progressIndex ? 'is-current' : '' }}"
                                                        data-story-progress="{{ $progressIndex }}"
                                                    ></div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <img
                                            src="{{ !empty($cisUpdate->img) ? webp_asset('storage/' . ltrim($cisUpdate->img, '/')) : webp_asset('landing/img/blog-1.png') }}"
                                            alt="{{ localized_value($cisUpdate, 'title') }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                        <div class="cis-story-overlay"></div>
                                        <div class="cis-story-content">
                                            <h2 class="fw-bold mb-4">
                                                {{ localized_value($cisUpdate, 'title') }}
                                            </h2>
                                            <a
                                                href="{{ $cisUpdate->url ?: '#' }}"
                                                class="btn btn-light rounded-pill px-4 py-2"
                                            >
                                                {{ __('front.read_more') }}
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="cis-story-empty">
                                <span class="cis-story-badge mb-3">
                                    <i class="fas fa-bolt"></i>
                                    CIS Update
                                </span>
                                <h2 class="display-5 fw-bold mb-3">{{ localized_value($aboutSchool, 'title', $settings['site_title'] ?? config('app.name')) }}</h2>
                                <div class="about-school-content mb-0">{!! safe_rich_text(localized_value($aboutSchool, 'content', $settings['site_description'] ?? ('Website resmi ' . config('app.name')))) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.1s">
                    <div class="bg-white rounded p-5 h-100">
                        <div class="row g-4 justify-content-center">
                            <div class="col-12">
                                <div class="rounded bg-primary p-4 shadow-sm">
                                    <!-- Menggunakan webp_asset untuk kompresi file image -->
                                    <img src="{{ webp_asset('landing/img/chalidana_title.png') }}" class="img-fluid rounded w-100" alt="Chalidana Title" loading="lazy" decoding="async">
                                </div>
                            </div>
                            @foreach($cisInNumbers as $item)
                                <div class="col-sm-6">
                                    <div class="counter-item bg-light rounded p-3 h-100">
                                        @if(!empty($item->icon))
                                            <div class="mb-2 text-primary fs-3">
                                                <i class="{{ $item->icon }}"></i>
                                            </div>
                                        @endif
                                        <div class="counter-counting">
                                            <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">{{ preg_replace('/[^0-9]/', '', $item->number) }}</span>
                                        </div>
                                        <h4 class="mb-1 text-dark">{{ localized_value($item, 'title') }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Campus Start -->
    <div class="container-fluid service py-5" id="campuses">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-narrow" data-wow-delay="0.1s">
                <h4 class="text-primary">{{ __('front.our_campuses') }}</h4>
                <h2 class="display-4 mb-4">{{ __('front.explore_our_campuses') }}</h2>
                <p class="mb-0">{{ __('front.explore_our_campuses_desc') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($campuses as $index => $campus)
                    <div class="col-md-6 col-xl-4 d-flex wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.1), 1) }}s">
                        @php
                            $campusMapUrl = null;
                            $campusMapQuery = null;

                            if (!empty($campus->maplocation)) {
                                $rawMapLocation = trim($campus->maplocation);

                                if (preg_match('/!2s([^!]+)!/i', $rawMapLocation, $matches)) {
                                    $campusMapQuery = urldecode($matches[1]);
                                } elseif (filter_var($rawMapLocation, FILTER_VALIDATE_URL) && !str_contains($rawMapLocation, '/maps/embed')) {
                                    $campusMapUrl = $rawMapLocation;
                                }
                            }

                            if (empty($campusMapUrl)) {
                                if (!empty($campusMapQuery)) {
                                    $campusMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($campusMapQuery);
                                } elseif (!empty($campus->address)) {
                                    $campusMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode(strip_tags($campus->address));
                                }
                            }

                            $campusLearnMoreUrl = !empty($campus->slug) ? route('landing.school.show', $campus->slug) : null;
                            $campusImagePath = 'landing/img/blog-1.png';

                            if (!empty($campus->img_header)) {
                                $campusImagePath = 'images/campuses/' . $campus->img_header;
                            }

                            $campusWebpImagePath = preg_replace('/\.[^.]+$/', '.webp', $campusImagePath);
                            $campusDisplayImagePath = file_exists(public_path($campusWebpImagePath))
                                ? $campusWebpImagePath
                                : $campusImagePath;
                        @endphp
                        <div class="campus-showcase-card">
                            <div class="campus-showcase-media">
                                <span class="campus-showcase-badge">
                                    <i class="fa fa-map-marker-alt"></i>
                                    {{ $campus->campusArea->title ?? __('front.campus') }}
                                </span>
                                <img
                                    src="{{ asset($campusDisplayImagePath) }}"
                                    alt="{{ $campus->title }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                            <div class="campus-showcase-content">
                                <h2 class="campus-showcase-title">{{ $campus->title }}</h2>
                                <div class="campus-showcase-info">
                                    <div class="campus-showcase-info-item">
                                        <span class="campus-showcase-info-icon">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <div>
                                            <span class="campus-showcase-info-label">{{ __('front.address_label') }}</span>
                                            <p class="campus-showcase-info-value">{!! safe_rich_text($campus->address ?: '-') !!}</p>
                                        </div>
                                    </div>
                                    <div class="campus-showcase-info-item">
                                        <span class="campus-showcase-info-icon">
                                            <i class="fa fa-id-card"></i>
                                        </span>
                                        <div>
                                            <span class="campus-showcase-info-label">{{ __('front.npsn_label') }}</span>
                                            <p class="campus-showcase-info-value is-code">{!! safe_rich_text($campus->NPSN ?: '-') !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="campus-showcase-actions">
                                    @if($campusMapUrl)
                                        <a class="btn btn-outline-primary rounded-pill py-2 px-4" href="{{ $campusMapUrl }}" target="_blank" rel="noopener noreferrer">
                                            <i class="fa fa-location-arrow me-2"></i>{{ __('front.view_map') }}
                                        </a>
                                    @endif
                                    @if($campusLearnMoreUrl)
                                        <a class="btn btn-primary rounded-pill py-2 px-4" href="{{ $campusLearnMoreUrl }}">
                                            {{ __('front.learn_more') }}
                                        </a>
                                    @else
                                        <button class="btn btn-primary rounded-pill py-2 px-4" type="button" disabled>
                                            {{ __('front.learn_more') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted">{{ __('front.no_campuses') }}</div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Campus End -->

    <!-- Blog Start -->
    <div class="container-fluid blog bg-light py-5 hex-silhouette-section">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-narrow" data-wow-delay="0.1s">
                <h4 class="text-primary">{{ __('front.from_blog') }}</h4>
                <h2 class="display-4 mb-4">{{ __('front.news_updates') }}</h2>
                <p class="mb-0">{{ __('front.news_updates_desc') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($events as $index => $event)
                    <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.1 + ($index * 0.1), 1) }}s">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img
                                    src="{{ $event->img ? webp_asset('images/events/' . $event->img) : webp_asset('landing/img/blog-1.png') }}"
                                    class="img-fluid rounded-top w-100"
                                    alt="{{ localized_value($event, 'title') }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <div class="blog-categiry py-2 px-4">
                                    <span>{{ $event->study->title ?? __('front.event') }}</span>
                                </div>
                            </div>
                            <div class="blog-content p-4 d-flex flex-column h-100">
                                <div class="blog-comment d-flex justify-content-between mb-3">
                                    <div class="small"><span class="fa fa-calendar text-primary"></span> {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->format('d M Y') : '-' }}</div>
                                    <div class="small"><span class="fa fa-folder text-primary"></span> {{ $event->study->title ?? __('front.general') }}</div>
                                </div>
                                <a href="{{ route('landing.events.show', $event->slug) }}" class="h4 d-inline-block mb-3">{{ localized_value($event, 'title') }}</a>
                                <p class="mb-3">{{ \Illuminate\Support\Str::limit(strip_tags(localized_value($event, 'content', '')), 120) }}</p>
                                <div class="mt-auto pt-3">
                                    <a href="{{ route('landing.events.show', $event->slug) }}" class="btn p-0">{{ __('front.read_more') }} <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted">{{ __('front.no_events') }}</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Blog End -->

    <!-- Testimonial Start -->
    <div class="container-fluid team py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-narrow" data-wow-delay="0.1s">
                <h4 class="text-primary">{{ __('front.testimonials') }}</h4>
                <h2 class="display-4 mb-4">{{ __('front.families_say') }}</h2>
                <p class="mb-0">{{ __('front.families_say_desc') }}</p>
            </div>
            <div class="testimonial-carousel owl-carousel">
                @forelse($testimonials as $index => $testimonial)
                    @php
                        $testimonialFullContent = trim(strip_tags($testimonial->content ?? ''));
                        $testimonialShortContent = \Illuminate\Support\Str::limit($testimonialFullContent, $testimonialExcerptLimit);
                    @endphp
                    <div class="testimonial-slide wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.15), 1) }}s">
                        <div class="team-item testimonial-card">
                            <div class="team-img">
                                <img
                                    src="{{ $testimonial->images ? webp_asset('images/testimonial/' . $testimonial->images) : webp_asset('landing/img/team-1.jpg') }}"
                                    class="img-fluid rounded-top w-100"
                                    alt="{{ $testimonial->student }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                            <div class="team-title p-4">
                                <div class="mb-2 text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= (int) $testimonial->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                                <h4 class="mb-1">{{ $testimonial->student }}</h4>
                                <small>{{ __('front.child_of') }}</small>
                                <p class="mb-2 testimonial-parent">
                                    {{ $testimonial->title }}
                                </p>
                                <p class="mb-2 small testimonial-meta">{{ $testimonial->grade->name ?? '-' }}{{ $testimonial->campus ? ' • ' . $testimonial->campus->title : '' }}</p>
                                <p class="mb-0 testimonial-content">{{ $testimonialShortContent }}</p>
                                <a
                                    href="{{ route('landing.testimonials') }}"
                                    class="btn btn-link p-0 mt-3 align-self-start testimonial-link"
                                >{{ __('front.view_testimonial_detail') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted">{{ __('front.no_testimonials') }}</div>
                    </div>
                @endforelse
            </div>
            @if($testimonials->isNotEmpty())
                <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="{{ route('landing.testimonials') }}" class="btn btn-primary rounded-pill py-3 px-5">
                        {{ __('front.view_all_testimonials') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- Testimonial End -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lazy load video modal saat dibuka saja
            const modalEl = document.getElementById('homeVideoModal');
            if (modalEl) {
                const videoSource = modalEl.querySelector('video source');
                modalEl.addEventListener('show.bs.modal', function () {
                    if (videoSource && videoSource.dataset.src && !videoSource.src) {
                        videoSource.src = videoSource.dataset.src;
                        videoSource.parentElement.load();
                    }
                });
                modalEl.addEventListener('hidden.bs.modal', function () {
                    const videoPlayer = modalEl.querySelector('video');
                    if (videoPlayer) {
                        videoPlayer.pause();
                    }
                });
            }

            const storyRoot = document.getElementById('cisUpdateStories');

            if (!storyRoot) {
                return;
            }

            const slides = Array.from(storyRoot.querySelectorAll('[data-story-slide]'));

            if (!slides.length) {
                return;
            }

            const duration = Number(storyRoot.dataset.storyDuration || 4500);
            const prevButton = storyRoot.querySelector('[data-story-prev]');
            const nextButton = storyRoot.querySelector('[data-story-next]');
            let activeIndex = 0;
            let timerId = null;

            function refreshProgressState() {
                slides.forEach((slide, slideIndex) => {
                    slide.querySelectorAll('.cis-story-progress-bar').forEach((bar, barIndex) => {
                        if (barIndex < activeIndex) {
                            bar.style.transitionDuration = '0ms';
                            bar.style.width = '100%';
                            return;
                        }

                        bar.style.transitionDuration = '0ms';
                        bar.style.width = '0';
                    });
                });

                window.requestAnimationFrame(function () {
                    const activeSlide = slides[activeIndex];

                    if (!activeSlide) {
                        return;
                    }

                    const activeBar = activeSlide.querySelectorAll('.cis-story-progress-bar')[activeIndex];

                    if (!activeBar) {
                        return;
                    }

                    activeBar.style.transitionDuration = duration + 'ms';
                    activeBar.style.width = '100%';
                });
            }

            function showSlide(nextIndex) {
                activeIndex = (nextIndex + slides.length) % slides.length;

                slides.forEach((slide, index) => {
                    slide.classList.toggle('is-active', index === activeIndex);
                });

                refreshProgressState();
            }

            function scheduleNext() {
                window.clearTimeout(timerId);
                timerId = window.setTimeout(function () {
                    showSlide(activeIndex + 1);
                    scheduleNext();
                }, duration);
            }

            if (prevButton) {
                prevButton.addEventListener('click', function () {
                    showSlide(activeIndex - 1);
                    scheduleNext();
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function () {
                    showSlide(activeIndex + 1);
                    scheduleNext();
                });
            }

            showSlide(0);
            scheduleNext();
        });
    </script>
@endsection