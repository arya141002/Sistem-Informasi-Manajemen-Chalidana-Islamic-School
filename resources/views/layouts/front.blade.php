<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-FYCH4DDEYE"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-FYCH4DDEYE');
    </script>
    
    <!-- Ahrefs Analytics -->
    <script>
      var ahrefs_analytics_script = document.createElement('script');
      ahrefs_analytics_script.async = true;
      ahrefs_analytics_script.src = 'https://analytics.ahrefs.com/analytics.js';
      ahrefs_analytics_script.setAttribute('data-key', 'l/u2f8fKLeaRyOoocXJQ2w');
      document.getElementsByTagName('head')[0].appendChild(ahrefs_analytics_script);
    </script>
        @php
            $currentLocale = current_locale();
            $siteName = $settings['site_name'] ?? get_system('site_name', config('app.name'));
            $siteTitle = $settings['site_title'] ?? get_system('site_title', $siteName);
            $siteDescription = $settings['site_description'] ?? get_system('site_description', 'Website resmi ' . $siteName);
            $siteKeywords = $settings['site_keywords'] ?? get_system('site_keywords', $siteName);
            $contactEmail = $settings['contact_email'] ?? get_system('contact_email', '');
            $contactPhone = $settings['contact_phone'] ?? get_system('contact_phone', '');
            $socialLinks = collect($socialMedias ?? [])->mapWithKeys(function ($socialMedia) {
                return [strtolower($socialMedia->title) => $socialMedia->link];
            });
        @endphp
        <meta charset="utf-8">
        <title>{{ $siteTitle }}</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="{{ $siteKeywords }}" name="keywords">
        <meta content="{{ $siteDescription }}" name="description">
        <meta content="{{ $siteName }}" name="author">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/CIS_Logo.webp') }}">
        @php
            $sameAs = $socialLinks->filter(fn ($link) => filled($link) && $link !== '#')->values()->all();
            $siteUrl = rtrim(config('app.url'), '/');
            $structuredData = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => ['Organization', 'EducationalOrganization'],
                        '@id' => $siteUrl . '/#organization',
                        'name' => $siteName,
                        'url' => $siteUrl,
                        'description' => $siteDescription,
                        'email' => $contactEmail ?: null,
                        'telephone' => $contactPhone ?: null,
                        'sameAs' => $sameAs,
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => $siteUrl . '/#website',
                        'url' => $siteUrl,
                        'name' => $siteName,
                        'publisher' => ['@id' => $siteUrl . '/#organization'],
                    ],
                    [
                        '@type' => 'WebPage',
                        '@id' => url()->current() . '#webpage',
                        'url' => url()->current(),
                        'name' => $siteTitle,
                        'description' => $siteDescription,
                        'isPartOf' => ['@id' => $siteUrl . '/#website'],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- CSS Kritis (Tetap dimuat langsung) -->
        <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landing/css/style.css') }}?v={{ filemtime(public_path('landing/css/style.css')) }}" rel="stylesheet">
        
        <!-- CSS Non-Kritis (Di-defer agar tidak memblokir render pertama) -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{ asset('landing/lib/animate/animate.min.css') }}" media="print" onload="this.media='all'"/>
        <link rel="stylesheet" href="{{ asset('landing/lib/lightbox/css/lightbox.min.css') }}" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="{{ asset('landing/lib/owlcarousel/assets/owl.carousel.min.css') }}" media="print" onload="this.media='all'">
    </head>

    <body class="landing-shell">

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary landing-spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Topbar Start -->
        <div class="container-fluid topbar px-0 px-lg-4 bg-light py-2">
            <div class="container">
                    <div class="col-lg-12 text-center text-lg-end">
                        <div class="d-flex justify-content-end">
                            <div class="d-flex border-end border-primary pe-3">
                                <a class="btn p-0 text-primary me-3" href="{{ $socialLinks->get('youtube', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                                <a class="btn p-0 text-primary me-3" href="{{ $socialLinks->get('tiktok', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                                <a class="btn p-0 text-primary me-3" href="{{ $socialLinks->get('instagram', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                <a class="btn p-0 text-primary me-0" href="{{ $socialLinks->get('linkedin', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                            <div class="dropdown ms-3">
                                <a href="#" class="dropdown-toggle text-dark" data-bs-toggle="dropdown"><small><i class="fas fa-globe-europe text-primary me-2"></i> {{ __('front.language_name') }}</small></a>
                                <div class="dropdown-menu rounded">
                                    <a href="{{ route('locale.switch', 'en') }}" class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}">English</a>
                                    <a href="{{ route('locale.switch', 'id') }}" class="dropdown-item {{ $currentLocale === 'id' ? 'active' : '' }}">Indonesia</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->
        <!-- Navbar & Hero Start -->
        <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light"> 
                    <a href="{{ url('/') }}" class="navbar-brand p-0">
                        <img src="{{ webp_asset('images/icon/CIS_Branding_Logo.webp') }}" alt="{{ $siteName }}" class="front-brand-logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    @php
                        $isHome = request()->url() === url('/');
                        $isAdmission = request()->is('admission');
                        $isAboutSchool = request()->is('about-us') || request()->is('curricula') || request()->is('school-history') || request()->is('school-leadership') || request()->is('why-choose') || request()->is('cis-in-numbers') || request()->is('cis-digital-library');
                        $isSchoolPage = request()->is('school/*');
                        $isChalidaniansPage = request()->is('important-dates') || request()->is('events') || request()->is('achievement') || request()->is('testimonials') || request()->is('cis-icon');
                        $isWhatsUpPage = request()->is('happenings') || request()->is('happenings/*') || request()->is('highlights') || request()->is('highlights/*') || request()->is('announcements') || request()->is('announcements/*') || request()->is('gallery') || request()->is('career');
                    @endphp
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto" >
                            <a href="{{ url('/') }}" class="nav-item nav-link {{ $isHome ? 'active' : '' }}">{{ __('front.home') }}</a>
                            <a href="{{ route('landing.admission') }}" class="nav-item nav-link {{ $isAdmission ? 'active' : '' }}">{{ __('front.admission') }}</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link {{ $isAboutSchool ? 'active' : '' }}" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">{{ __('front.about_school') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ url('/about-us') }}" class="dropdown-item {{ request()->is('about-us') ? 'active' : '' }}">{{ __('front.about_us') }}</a>
                                    <a href="{{ route('landing.curricula') }}" class="dropdown-item {{ request()->is('curricula') ? 'active' : '' }}">{{ __('front.curricula') }}</a>
                                    <a href="{{ route('landing.school-history') }}" class="dropdown-item {{ request()->is('school-history') ? 'active' : '' }}">{{ __('front.school_history') }}</a>
                                    <a href="{{ route('landing.school-leadership') }}" class="dropdown-item {{ request()->is('school-leadership') ? 'active' : '' }}">{{ __('front.school_leadership') }}</a>
                                    <a href="{{ url('/why-choose') }}" class="dropdown-item {{ request()->is('why-choose') ? 'active' : '' }}">{{ __('front.why_choose_cis') }}</a>
                                    <a href="{{ url('/cis-in-numbers') }}" class="dropdown-item {{ request()->is('cis-in-numbers') ? 'active' : '' }}">{{ __('front.cis_in_numbers') }}</a>
                                    <a href="https://kubuku.id/download/cis-digital-library/" class="dropdown-item" target="_blank" rel="noopener noreferrer">{{ __('front.cis_digital_library') }}</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link {{ $isSchoolPage ? 'active' : '' }}" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">{{ __('front.school') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    @forelse($frontCampuses ?? [] as $frontCampus)
                                        <a
                                            href="{{ !empty($frontCampus->slug) ? route('landing.school.show', $frontCampus->slug) : url('/#campuses') }}"
                                            class="dropdown-item {{ request()->is('school/' . $frontCampus->slug) ? 'active' : '' }}"
                                        >{{ $frontCampus->title }}</a>
                                    @empty
                                        <span class="dropdown-item text-muted">{{ __('front.no_campuses') }}</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link {{ $isChalidaniansPage ? 'active' : '' }}" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">{{ __('front.chalidanians') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ url('/important-dates') }}" class="dropdown-item {{ request()->is('important-dates') ? 'active' : '' }}">{{ __('front.important_dates') }}</a>
                                    <a href="{{ route('landing.events') }}" class="dropdown-item {{ request()->is('events') ? 'active' : '' }}">{{ __('front.events') }}</a>
                                    <a href="{{ route('landing.achievement') }}" class="dropdown-item {{ request()->is('achievement') ? 'active' : '' }}">{{ __('front.achievement') }}</a>
                                    <a href="{{ route('landing.testimonials') }}" class="dropdown-item {{ request()->is('testimonials') ? 'active' : '' }}">{{ __('front.testimonials') }}</a>
                                    <a href="{{ route('landing.cis-icon') }}" class="dropdown-item {{ request()->is('cis-icon') ? 'active' : '' }}">{{ __('front.cis_icon') }}</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link {{ $isWhatsUpPage ? 'active' : '' }}" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">{{ __('front.whats_up_cis') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('landing.whatsup.highlights') }}" class="dropdown-item {{ request()->is('highlights') || request()->is('highlights/*') ? 'active' : '' }}">{{ __('front.highlights') }}</a>
                                    <a href="{{ route('landing.whatsup.announcements') }}" class="dropdown-item {{ request()->is('announcements') || request()->is('announcements/*') ? 'active' : '' }}">{{ __('front.announcements') }}</a>
                                    <a href="{{ route('landing.gallery') }}" class="dropdown-item {{ request()->is('gallery') ? 'active' : '' }}">{{ __('front.gallery') }}</a>
                                    <a href="{{ route('landing.career') }}" class="dropdown-item {{ request()->is('career') ? 'active' : '' }}">{{ __('front.career') }}</a>
                                </div>
                            </div>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">{{ __('front.school_tour') }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    @php
                                        $tourCampuses = collect($frontCampuses ?? [])->filter(fn ($frontCampus) => !empty($frontCampus->school_tour));
                                    @endphp
                                    @forelse($tourCampuses as $frontCampus)
                                        @if(!empty($frontCampus->school_tour))
                                            <button
                                                type="button"
                                                class="dropdown-item js-school-tour-trigger"
                                                data-school-tour-url="{{ $frontCampus->school_tour }}"
                                                data-school-tour-title="{{ $frontCampus->title }}"
                                            >{{ $frontCampus->title }}</button>
                                        @endif
                                    @empty
                                        <span class="dropdown-item text-muted">{{ __('front.no_campuses') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar & Hero End -->
        <div class="modal fade school-tour-modal" id="schoolTourModal" tabindex="-1" aria-labelledby="schoolTourModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content shadow-lg">
                    <div class="modal-header px-4 py-3">
                        <h5 class="modal-title" id="schoolTourModalLabel">{{ __('front.school_tour') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="school-tour-frame">
                            <iframe
                                id="schoolTourFrame"
                                src=""
                                title="{{ __('front.school_tour') }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                            <div class="school-tour-frame__empty d-none" id="schoolTourEmpty">
                                {{ __('front.school_tour_unavailable') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('landing') 
        <!-- Footer Start -->
        <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-xl-12">
                        <div class="mb-5">
                            <div class="row g-4">
                                <div class="col-md-6 col-lg-6 col-xl-5">
                                    <div class="footer-item">
                                        <a href="{{ url('/') }}" class="p-0">
                                            <img src="{{ webp_asset('images/icon/logo-branding-CIS-01.png') }}" alt="{{ $siteName }}" class="front-brand-logo">
                                        </a>
                                        <br>
                                        <p class="text-white mb-4 footer-tagline">{{ localized_value($tagline, 'jargon', $siteDescription) }}</p>
                                        @if($contactEmail || $contactPhone)
                                            <div class="text-white-50 mb-4">
                                                @if($contactEmail)
                                                    <div><a class="text-white-50" href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="footer-btn d-flex">
                                            <a class="btn btn-md-square rounded-circle me-3" href="{{ $socialLinks->get('youtube', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                                            <a class="btn btn-md-square rounded-circle me-3" href="{{ $socialLinks->get('tiktok', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                                            <a class="btn btn-md-square rounded-circle me-3" href="{{ $socialLinks->get('instagram', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                            <a class="btn btn-md-square rounded-circle me-0" href="{{ $socialLinks->get('linkedin', '#') }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-3">
                                    <div class="footer-item">
                                        <h4 class="text-white mb-4">{{ __('front.addresses') }}</h4>
                                        @forelse($campusAreas ?? [] as $campusArea)
                                            <div class="mb-3">
                                                <h6 class="text-white mb-1">{{ $campusArea->title }}</h6>
                                                <div class="footer-campus-address mb-0">{!! localized_value($campusArea, 'address') !!}</div>
                                            </div>
                                        @empty
                                            <p class="text-white-50 mb-0">{{ __('front.no_campus_addresses') }}</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="footer-item">
                                        <h4 class="mb-4 text-white">{{ __('front.information') }}</h4>
                                        @forelse($campusAreas ?? [] as $campusArea)
                                            <div class="mb-3">
                                                <h6 class="text-white mb-1">{{ $campusArea->title }}</h6>
                                                <div class="footer-campus-address mb-0">{{ $campusArea->phone }}</div>
                                            </div>
                                        @empty
                                            <p class="text-white mb-0">{{ __('front.no_campus_phones') }}</p>
                                        @endforelse
                                        <div class="mt-4">
                                            <a class="text-white-50 d-block mb-2" href="{{ url('/contact') }}">Contact</a>
                                            <a class="text-white-50 d-block" href="{{ url('/terms') }}">Terms</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="partner-showcase pt-5">
                            <div class="partner-showcase-panel">
                                <div class="row g-4 align-items-center">
                                    <div class="col-xl-3">
                                        <div class="partner-heading text-center text-xl-start">
                                            <span class="partner-kicker">{{ __('front.our_partners') }}</span>
                                            <h3 class="text-white mt-2 mb-3">Trusted By Institutions That Share Our Vision</h3>
                                            <p class="text-white-50 mb-0">{{ __('front.our_partners_desc') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-9">
                                        <div class="row g-3">
                                            <div class="col-6 col-md-4 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/logo_kemendikbud.png') }}" alt="Kemendikbud">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/micro.webp') }}" alt="Microsoft Partner">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/logo-anps-text.png') }}" alt="ANPS">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/AISEI-Logo-1-300x100.png') }}" alt="AISEI">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/logo_ummi.png') }}" alt="UMMI">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/Lambang-UM.webp') }}" alt="UMMI">
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6 col-xl">
                                                <div class="partner-logo-card">
                                                    <img src="{{ webp_asset('landing/img/partner/ibnusina.webp') }}" alt="UMMI">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-4 px-lg-5 mb-4 text-center">
                <div class="small text-white">
                    {{ __('Copyright') }} &copy; {{ date('Y') }} - Chalidana Islamic School | {{ __('All Rights Reserved') }}
                </div>
            
                <h6 class="mt-2 text-white">Visitor :</h6>
            
                <div class="d-flex justify-content-center align-items-center small mb-2">
                    <a href="https://www.hitwebcounter.com" target="_blank" rel="noopener noreferrer">
                        <img src="https://www.hitwebcounter.com/counter/counter.php?page=8049555&style=0025&nbdigits=5&type=ip&initCount=0" 
                             title="Free Counter" 
                             alt="web counter" 
                             style="width:80px; height:auto; display:inline-block;" 
                             border="0"
                             referrerpolicy="no-referrer-when-downgrade" />
                    </a>
                </div>
                <small class="text-white">since 10/10/2022</small>
            </div>
        </div>
        
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('landing/lib/wow/wow.min.js') }}"></script>
        <script src="{{ asset('landing/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('landing/lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('landing/lib/counterup/counterup.min.js') }}"></script>
        <script src="{{ asset('landing/lib/lightbox/js/lightbox.min.js') }}"></script>
        <script src="{{ asset('landing/lib/owlcarousel/owl.carousel.min.js') }}"></script>
        

        <!-- Template Javascript -->
        <script src="{{ asset('landing/js/main.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('schoolTourModal');
                const frameElement = document.getElementById('schoolTourFrame');
                const emptyStateElement = document.getElementById('schoolTourEmpty');
                const titleElement = document.getElementById('schoolTourModalLabel');

                if (!modalElement || !frameElement || !window.bootstrap) {
                    return;
                }

                const schoolTourModal = new bootstrap.Modal(modalElement);

                function normalizeSchoolTourUrl(rawValue) {
                    if (!rawValue) {
                        return null;
                    }

                    let cleanedValue = String(rawValue).trim().replace(/&amp;/g, '&');
                    const iframeMatch = cleanedValue.match(/src=["']([^"']+)["']/i);

                    cleanedValue = iframeMatch ? iframeMatch[1] : cleanedValue;

                    if (/^www\./i.test(cleanedValue)) {
                        cleanedValue = 'https://' + cleanedValue;
                    }

                    if (/^\/\//.test(cleanedValue)) {
                        cleanedValue = window.location.protocol + cleanedValue;
                    }

                    return cleanedValue;
                }

                function resolveYoutubeEmbedUrl(rawValue) {
                    const normalizedUrl = normalizeSchoolTourUrl(rawValue);

                    if (!normalizedUrl) {
                        return null;
                    }

                    try {
                        const parsedUrl = new URL(normalizedUrl, window.location.origin);
                        const host = parsedUrl.hostname.replace(/^www\./, '');
                        const segments = parsedUrl.pathname.split('/').filter(Boolean);

                        if (host === 'youtu.be' && segments[0]) {
                            return 'https://www.youtube.com/embed/' + segments[0] + '?autoplay=1&rel=0';
                        }

                        if (host === 'youtube-nocookie.com' || host === 'www.youtube-nocookie.com') {
                            if (segments[0] === 'embed' && segments[1]) {
                                return 'https://www.youtube-nocookie.com/embed/' + segments[1] + '?autoplay=1&rel=0';
                            }
                        }

                        if (host.includes('youtube.com')) {
                            if (parsedUrl.searchParams.get('v')) {
                                return 'https://www.youtube.com/embed/' + parsedUrl.searchParams.get('v') + '?autoplay=1&rel=0';
                            }

                            const embedIndex = segments.findIndex(function (segment) {
                                return ['embed', 'shorts', 'live'].includes(segment);
                            });

                            if (embedIndex !== -1 && segments[embedIndex + 1]) {
                                return 'https://www.youtube.com/embed/' + segments[embedIndex + 1] + '?autoplay=1&rel=0';
                            }
                        }
                    } catch (error) {
                        const fallbackMatch = normalizedUrl.match(/(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:watch\?v=|embed\/|shorts\/|live\/))([A-Za-z0-9_-]{6,})/i);
                        if (fallbackMatch) {
                            return 'https://www.youtube.com/embed/' + fallbackMatch[1] + '?autoplay=1&rel=0';
                        }
                    }

                    return null;
                }

                function openSchoolTour(url, title) {
                    const embedUrl = resolveYoutubeEmbedUrl(url);

                    if (titleElement) {
                        titleElement.textContent = title
                            ? '{{ __('front.school_tour') }} - ' + title
                            : '{{ __('front.school_tour') }}';
                    }

                    if (embedUrl) {
                        frameElement.src = embedUrl;
                        frameElement.classList.remove('d-none');
                        emptyStateElement.classList.add('d-none');
                        schoolTourModal.show();
                        return;
                    }

                    frameElement.src = '';
                    frameElement.classList.add('d-none');
                    emptyStateElement.classList.remove('d-none');
                    schoolTourModal.show();
                }

                document.querySelectorAll('.js-school-tour-trigger').forEach(function (trigger) {
                    trigger.addEventListener('click', function () {
                        window.setTimeout(function () {
                            openSchoolTour(
                                trigger.getAttribute('data-school-tour-url'),
                                trigger.getAttribute('data-school-tour-title')
                            );
                        }, 120);
                    });
                });

                modalElement.addEventListener('hidden.bs.modal', function () {
                    frameElement.src = '';
                    frameElement.classList.remove('d-none');
                    emptyStateElement.classList.add('d-none');
                    if (titleElement) {
                        titleElement.textContent = '{{ __('front.school_tour') }}';
                    }
                });

                if (navigator.modelContext && typeof navigator.modelContext.registerTool === 'function') {
                    navigator.modelContext.registerTool({
                        name: 'find_school_information',
                        description: 'Find official information about Chalidana Islamic School, including admissions, programs, campuses, and contact details.',
                        inputSchema: {
                            type: 'object',
                            properties: {
                                topic: {
                                    type: 'string',
                                    description: 'The school information topic to find.'
                                }
                            },
                            required: ['topic']
                        },
                        execute: async function ({ topic }) {
                            const response = await fetch('/llms.txt', {
                                headers: { Accept: 'text/plain' }
                            });
                            if (!response.ok) {
                                throw new Error('School information is temporarily unavailable.');
                            }
                            return { topic: topic, content: await response.text() };
                        }
                    });
                }

                const homeVideoModalElement = document.getElementById('homeVideoModal');
                const homeVideoPlayer = document.getElementById('homeVideoModalPlayer');

                if (homeVideoModalElement && homeVideoPlayer) {
                    const homeVideoModal = new bootstrap.Modal(homeVideoModalElement);

                    homeVideoModalElement.addEventListener('show.bs.modal', function () {
                        homeVideoPlayer.currentTime = 0;
                        homeVideoPlayer.play().catch(function () {
                            // Fallback if browser blocks autoplay with sound
                        });
                    });

                    homeVideoModalElement.addEventListener('hidden.bs.modal', function () {
                        homeVideoPlayer.pause();
                        homeVideoPlayer.currentTime = 0;
                    });
                }
            });
        </script>
    </body>

</html>
