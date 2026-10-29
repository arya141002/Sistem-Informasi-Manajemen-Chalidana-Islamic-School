@extends('layouts.front')
@section('landing')
    @php
        $campusTitle = $campus->title;
        $campusAreaLabel = $campus->campusArea->title ?? __('front.school');
        $campusSubject = localized_value($campus, 'subject', __('front.school'));
        $campusContent = localized_value($campus, 'content', '');
        $principalTitle = localized_value($campus, 'from', __('front.principal_title'));
        $principalMessage = localized_value($campus, 'message', '');
        $teacherGroups = [
            'teacher' => __('front.teachers'),
            'staff' => __('front.staff'),
            'ob' => __('front.office_support'),
        ];
        $campusMapSource = null;
        $campusMapUrl = null;

        if (!empty($campus->maplocation)) {
            $rawMapLocation = trim($campus->maplocation);

            if (filter_var($rawMapLocation, FILTER_VALIDATE_URL)) {
                if (str_contains($rawMapLocation, '/maps/embed')) {
                    $campusMapSource = $rawMapLocation;
                } else {
                    $campusMapUrl = $rawMapLocation;
                }
            }

            if (!$campusMapSource && preg_match('/src="([^"]+)"/i', $rawMapLocation, $matches)) {
                $campusMapSource = $matches[1];
            }

            if (!$campusMapUrl && preg_match('/!2s([^!]+)!/i', $rawMapLocation, $matches)) {
                $campusMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode(urldecode($matches[1]));
            }
        }

        if (!$campusMapUrl && !empty($campus->address)) {
            $campusMapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode(strip_tags($campus->address));
        }

        $campusHeaderImage = null;
        if (!empty($campus->img_header)) {
            $campusHeaderImage = asset('images/campuses/' . $campus->img_header);
        }
    @endphp

    <div class="container-fluid bg-breadcrumb bg-breadcrumb-campus-detail" @if($campusHeaderImage) style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.2) 60%, rgba(1, 95, 201, 0.9) 100%), url('{{ $campusHeaderImage }}');" @endif>
        <div class="container-fluid page-title-corner py-5 page-title-container text-start">
            <div class="container">
                <h4 class="text-white display-4 mb-2 text-nowrap wow fadeInDown" data-wow-delay="0.1s">{{ $campusTitle }}</h4>
                <ol class="breadcrumb d-flex justify-content-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ __('front.school') }}</a></li>
                    <li class="breadcrumb-item active text-primary">{{ $campusTitle }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid school-detail-page py-5">
        <div class="container py-5">
            {{-- <section class="school-detail-hero mb-5">
                <div class="row g-0 align-items-stretch">
                    <div class="col-lg-6">
                        <div class="school-detail-copy wow fadeInUp" data-wow-delay="0.2s">
                            <span class="school-detail-kicker">{{ $campusAreaLabel }}</span>
                            <h1 class="display-4 mb-3">{{ $campusTitle }}</h1>
                            <p class="school-detail-subject mb-4">{{ $campusSubject }}</p>
                            <div class="school-detail-meta">
                                <span><i class="fa fa-id-card me-2"></i>{{ __('front.npsn') }}: {{ $campus->NPSN ?: '-' }}</span>
                                <span><i class="fa fa-map-marker-alt me-2"></i>{{ strip_tags($campus->address ?: '-') }}</span>
                            </div>
                            <div class="school-detail-actions mt-4">
                                @if($campusMapUrl)
                                    <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="{{ $campusMapUrl }}" target="_blank" rel="noopener noreferrer">
                                        {{ __('front.view_map') }}
                                    </a>
                                @endif
                                @if($campus->school_tour)
                                    <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ $campus->school_tour }}" target="_blank" rel="noopener noreferrer">
                                        {{ __('front.school_tour') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="school-detail-media wow fadeInUp" data-wow-delay="0.3s">
                            @if($campus->img_header)
                                <img src="{{ webp_asset('images/campuses/' . $campus->img_header) }}" alt="{{ $campusTitle }}">
                            @else
                                <div class="school-detail-placeholder">{{ $campusTitle }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section> --}}

            <section class="school-detail-section mb-5">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7">
                        <article class="school-detail-panel wow fadeInUp" data-wow-delay="0.2s">
                            <span class="school-detail-eyebrow">{{ __('front.about_this_campus') }}</span>
                            <h2 class="mb-3">{{ $campusTitle }}</h2>
                            <div class="school-detail-richtext">
                                {!! $campusContent ?: '<p>' . e(__('front.school_content_empty')) . '</p>' !!}
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-5">
                        <aside class="school-detail-panel school-detail-panel--accent wow fadeInUp" data-wow-delay="0.3s">
                            <span class="school-detail-eyebrow">{{ __('front.quick_info') }}</span>
                            <ul class="school-detail-facts">
                                <li>
                                    <span>{{ __('front.school_level') }}</span>
                                    <strong>{{ $campusSubject }}</strong>
                                </li>
                                <li>
                                    <span>{{ __('front.area') }}</span>
                                    <strong>{{ $campusAreaLabel }}</strong>
                                </li>
                                <li>
                                    <strong>{!! $campus->NPSN ?: '-' !!}</strong>
                                </li>
                                <li>
                                    <span>{{ __('front.addresses') }}</span>
                                    <strong>{!! $campus->address ?: '-' !!}</strong>
                                </li>
                            </ul>
                        </aside>
                    </div>
                </div>
            </section>
            <section class="school-detail-section mb-5">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-5">
                        <div class="school-detail-principal-media wow fadeInUp" data-wow-delay="0.2s">
                            @if($campus->img_principal)
                                <img src="{{ webp_asset('images/campuses/' . $campus->img_principal) }}" alt="{{ $principalTitle }}">
                            @else
                                <div class="school-detail-placeholder">{{ $principalTitle }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <article class="school-detail-panel wow fadeInUp" data-wow-delay="0.3s">
                            <span class="school-detail-eyebrow">{{ __('front.from_our_principal') }}</span>
                            <h2 class="mb-2">{{ $principalTitle }}</h2>
                            <h3 class="school-detail-principal-name mb-4">{{ $campusTitle }}</h3>
                            <div class="school-detail-richtext">
                                {!! $principalMessage ?: '<p>' . e(__('front.principal_message_empty')) . '</p>' !!}
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            @foreach($teacherGroups as $role => $label)
                @php
                    $members = $teachersAndStaff->get($role, collect());
                @endphp
                @if($members->isNotEmpty())
                    <section class="school-detail-section mb-5">
                        <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                            <div>
                                <span class="school-detail-eyebrow">{{ __('front.meet_our_team') }}</span>
                                <h2 class="mb-0">{{ $label }}</h2>
                            </div>
                            <div class="school-detail-count">{{ $members->count() }} {{ __('front.team_members') }}</div>
                        </div>
                        <div class="row g-4">
                            @foreach($members as $index => $member)
                                <div class="col-sm-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.1), 1) }}s">
                                    <article class="school-team-card">
                                        <div class="school-team-card__media">
                                            <img
                                                src="{{ $member->img ? webp_asset('images/teachernstaff/' . $member->img) : webp_asset('landing/img/team-1.jpg') }}"
                                                alt="{{ strip_tags($member->content) }}"
                                            >
                                        </div>
                                        <div class="school-team-card__body">
                                            <div class="school-team-card__content">{!! $member->content !!}</div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach

           @if($campus->facilities->isNotEmpty())
                <section class="school-detail-section mb-5">
                    <div class="mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <span class="school-detail-eyebrow">{{ __('front.campus_facilities') }}</span>
                        <h2 class="mb-0">{{ __('front.learning_spaces') }}</h2>
                    </div>
                    <div class="row g-4">
                        @foreach($campus->facilities as $index => $facility)
                            <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.1), 1) }}s">
                                <article class="school-facility-card position-relative overflow-hidden rounded-4">
                                    <!-- Gambar Facility -->
                                    <div class="school-facility-card__media">
                                        <img
                                            src="{{ $facility->img ? webp_asset('images/facility/' . $facility->img) : webp_asset('landing/img/blog-1.png') }}"
                                            alt="{{ localized_value($facility, 'title', __('front.campus_facilities')) }}"
                                            class="w-100 object-fit-cover d-block"
                                            style="height: 240px;"
                                        >
                                    </div>
                                    
                                    <!-- Overlay background gradasi gelap & Teks -->
                                    <div class="school-facility-card__overlay position-absolute bottom-0 start-0 w-100 p-3 pt-5"
                                         style="background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.4) 60%, transparent 100%);">
                                        <h3 class="h6 text-white fw-semibold mb-0">
                                            {{ localized_value($facility, 'title', __('front.campus_facilities')) }}
                                        </h3>
                                        @if(localized_value($facility, 'content'))
                                            <div class="school-facility-card__content small text-white-50 mt-1">
                                                {!! localized_value($facility, 'content') !!}
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($campusMapSource || $campusMapUrl)
                <section class="school-detail-section mb-4">
                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-4">
                            <article class="school-detail-panel h-100 wow fadeInUp" data-wow-delay="0.2s">
                                <span class="school-detail-eyebrow">{{ __('front.visit_us') }}</span>
                                <h2 class="mb-3">{{ __('front.find_our_campus') }}</h2>
                                <div class="school-detail-richtext">
                                    <p>{!! $campus->address ?: '-' !!}</p>
                                </div>
                                @if($campusMapUrl)
                                    <a class="btn btn-primary rounded-pill py-3 px-5 mt-3" href="{{ $campusMapUrl }}" target="_blank" rel="noopener noreferrer">
                                        {{ __('front.open_in_maps') }}
                                    </a>
                                @endif
                            </article>
                        </div>
                        <div class="col-lg-8">
                            <div class="school-map-card wow fadeInUp" data-wow-delay="0.3s">
                                @if($campusMapSource)
                                    <iframe
                                        src="{{ $campusMapSource }}"
                                        width="100%"
                                        height="100%"
                                        class="school-map-frame"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                    ></iframe>
                                @else
                                    <div class="school-map-card__fallback">
                                        <p class="mb-3">{{ __('front.map_embed_unavailable') }}</p>
                                        <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="{{ $campusMapUrl }}" target="_blank" rel="noopener noreferrer">
                                            {{ __('front.open_in_maps') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            <section class="school-detail-section mb-2">
                <div class="p-4 bg-light rounded-4 border text-center wow fadeInUp" data-wow-delay="0.2s">
                    <span class="school-detail-eyebrow d-block mb-1">{{ __('front.registration_info') }}</span>
                    <h3 class="h4 mb-3">{{ __('front.download_school_brochure') }}</h3>
                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-3">
                        <a href="{{ asset('brosur/E-Brochure_CIS_Malang_2024_2025.pdf') }}" class="btn btn-primary rounded-pill px-4 py-2" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i>{{ __('front.brochure') }} CIS Malang
                        </a>
                        <a href="{{ asset('brosur/E-Brosur_CIS_Menganti_2024-2025.pdf') }}" class="btn btn-primary rounded-pill px-4 py-2" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i>{{ __('front.brochure') }} CIS Gresik
                        </a>
                        <a href="{{ asset('brosur/E-Brosur_CIS_Juanda_2024-2025.pdf') }}" class="btn btn-primary rounded-pill px-4 py-2" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i>{{ __('front.brochure') }} CIS Sidoarjo
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
