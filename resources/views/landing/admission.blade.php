@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb bg-breadcrumb-admission">
        <div class="container page-title-corner py-5 page-title-container text-start ml-4">
            <h4 class="text-white display-4 mb-0 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.admission') }}</h4>
            <ol class="breadcrumb d-flex justify-content-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.admission') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="wow fadeInUp content-heading-narrow" data-wow-delay="0.1s">
                        <h4 class="text-primary">{{ __('front.admission') }}</h4>
                        <h1 class="display-5 mb-3">{{ __('front.admission_page_title') }}</h1>
                        <p class="mb-4">{{ __('front.admission_page_desc') }}</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ $admissionApplyUrl }}" class="btn btn-primary rounded-pill px-4 py-3">
                            <!-- <a href="https://ppdb.chalidanaislamicschool.sch.id" class="btn btn-primary rounded-pill px-4 py-3" target="_blank" rel="noopener noreferrer"> -->
                                <span class="fa fa-paper-plane me-2"></span>{{ __('front.admission_apply_now') }}
                            </a>
                            @if(!empty($settings['contact_email']))
                                <a href="mailto:{{ $settings['contact_email'] }}" class="btn btn-outline-primary rounded-pill px-4 py-3">
                                    <span class="fa fa-envelope me-2"></span>{{ __('front.admission_contact_team') }}
                                </a>
                            @endif
                        </div>
                        <div class="text-muted small mt-3">
                            <span class="fa fa-shield-alt text-primary me-1"></span>{{ __('front.admission_ppdb_note') }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <img src="{{ webp_asset('landing/img/resource/DSC00314.jpg') }}" class="img-fluid rounded shadow-sm w-100" alt="{{ __('front.admission') }}">
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-end mb-4">
                <div class="col-lg-8">
                    <div class="wow fadeInUp content-heading-narrow" data-wow-delay="0.1s">
                        <h4 class="text-primary">{{ __('front.admission_process_title') }}</h4>
                        <h2 class="mb-3">{{ __('front.admission_hero_title') }}</h2>
                        <p class="mb-0">{{ __('front.admission_hero_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                @foreach(trans('front.admission_steps') as $index => $step)
                    <div class="col-md-6 col-xl">
                        <article class="bg-white rounded shadow-sm p-4 h-100 wow fadeInUp" data-wow-delay="{{ number_format(0.1 + ($index * 0.06), 2) }}s">
                            <div class="btn-md-square rounded-circle bg-primary text-white mb-4">{{ $index + 1 }}</div>
                            <h5 class="mb-2">{{ $step['title'] }}</h5>
                            <p class="text-muted mb-0">{{ $step['desc'] }}</p>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-7">
                    <div class="bg-white rounded shadow-sm p-4 p-lg-5 h-100 wow fadeInUp" data-wow-delay="0.1s">
                        <h4 class="text-primary mb-2">{{ __('front.admission_documents_title') }}</h4>
                        <p class="mb-4">{{ __('front.admission_documents_desc') }}</p>
                        <div class="row g-3">
                            @foreach(trans('front.admission_documents') as $document)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="btn-sm-square rounded-circle bg-primary text-white flex-shrink-0">
                                            <span class="fa fa-check"></span>
                                        </span>
                                        <div>{{ $document }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="bg-primary text-white rounded shadow-sm p-4 p-lg-5 h-100 wow fadeInUp" data-wow-delay="0.2s">
                        <h3 class="text-white mb-3">{{ __('front.admission_portal_title') }}</h3>
                        <p class="text-white-50 mb-4">{{ __('front.admission_portal_desc') }}</p>
                        <a href="{{ $admissionApplyUrl }}" class="btn btn-light rounded-pill px-4 py-3">
                        <!-- <a href="https://ppdb.chalidanaislamicschool.sch.id" class="btn btn-light rounded-pill px-4 py-3" target="_blank" rel="noopener noreferrer"> -->
                            <span class="fa fa-external-link-alt me-2"></span>{{ __('front.admission_apply_now') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm p-4 p-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h4 class="text-primary mb-2">{{ __('front.admission_contact_title') }}</h4>
                        <h2 class="mb-3">{{ __('front.admission_contact_team') }}</h2>
                        <p class="mb-0">{{ __('front.admission_contact_desc') }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column gap-3">
                            @if(!empty($settings['contact_email']))
                                <a href="mailto:{{ $settings['contact_email'] }}" class="btn btn-outline-primary rounded-pill px-4 py-3 text-start d-flex align-items-center mw-100">
                                    <span class="fa fa-envelope me-2 flex-shrink-0"></span>
                                    <span class="text-truncate" title="{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</span>
                                </a>
                            @endif
                            @php
                                $campusPhones = collect($campusAreas ?? [])
                                    ->filter(fn ($campus) => filled(trim($campus->phone ?? '')));
                            @endphp
                            
                            @if($campusPhones->isNotEmpty())
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($campusPhones as $campusArea)
                                        @php
                                            $campusPhone = trim($campusArea->phone);
                                            $campusPhoneLink = preg_replace('/[^0-9+]/', '', $campusPhone);
                                        @endphp
                            
                                        <a href="tel:{{ $campusPhoneLink }}"
                                           class="btn btn-outline-primary rounded-pill px-4 py-3 text-start d-flex align-items-center mw-100">
                                            <span class="fa fa-phone-alt me-2 flex-shrink-0"></span>
                                            <span class="text-truncate">
                                                <small class="d-block lh-sm opacity-75">{{ $campusArea->title }}</small>
                                                {{ $campusPhone }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ $admissionApplyUrl }}" class="btn btn-primary rounded-pill px-4 py-3 text-start d-flex align-items-center">
                            <!-- <a href="https://ppdb.chalidanaislamicschool.sch.id" class="btn btn-primary rounded-pill px-4 py-3 text-start d-flex align-items-center" target="_blank" rel="noopener noreferrer"> -->
                                <span class="fa fa-paper-plane me-2 flex-shrink-0"></span>
                                <span>{{ __('front.admission_apply_now') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
