@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.career') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.career') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            @if(session('career_application_success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 wow fadeInUp" id="career-application" data-wow-delay="0.1s">
                    {{ session('career_application_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4 wow fadeInUp" id="career-application" data-wow-delay="0.1s">
                    <strong>{{ __('front.career_application_error_title') }}</strong>
                    <div>{{ __('front.career_application_error_desc') }}</div>
                </div>
            @endif

            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-7">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.career') }}</h4>
                        <h1 class="display-4 mb-3">{{ __('front.career_page_title') }}</h1>
                        <p class="mb-0">{{ __('front.career_page_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form method="GET" action="{{ route('landing.career') }}" class="wow fadeInUp" data-wow-delay="0.25s">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="q" class="form-label fw-semibold">{{ __('front.search') }}</label>
                                <input type="text" id="q" name="q" class="form-control" value="{{ $search }}" placeholder="{{ __('front.career_search_placeholder') }}">
                            </div>
                            <div class="col-sm-7">
                                <label for="status" class="form-label fw-semibold">{{ __('front.career_filter_label') }}</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">{{ __('front.career_filter_all') }}</option>
                                    <option value="open" @selected($status === 'open')>{{ __('front.career_filter_open') }}</option>
                                    <option value="closed" @selected($status === 'closed')>{{ __('front.career_filter_closed') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-5 d-flex align-items-end">
                                <button class="btn btn-primary px-4 w-100" type="submit">{{ __('front.career_filter_button') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                @forelse($careers as $index => $career)
                    @php
                        $isOpen = (int) $career->career_status === 1;
                        $isSubmittedCareer = (string) old('career_id') === (string) $career->id;
                    @endphp
                    <div class="col-12 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.08), 2) }}s">
                        <article class="bg-white rounded shadow-sm p-4 p-lg-5 h-100">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 mb-4">
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                        <span class="badge rounded-pill px-3 py-2 {{ $isOpen ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $isOpen ? __('front.career_open') : __('front.career_closed') }}
                                        </span>
                                        @if($career->tanggal)
                                            <span class="text-muted small">
                                                {{ __('front.career_posted_on') }}: {{ \Carbon\Carbon::parse($career->tanggal)->translatedFormat('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="mb-2">{{ localized_value($career, 'title') }}</h3>
                                    <div class="text-muted small">
                                        {{ __('front.career_status_label') }}: {{ $isOpen ? __('front.career_filter_open') : __('front.career_filter_closed') }}
                                    </div>
                                </div>
                                <div class="text-lg-end">
                                    <a href="#career-{{ $career->id }}" class="btn btn-outline-primary rounded-pill px-4 py-2" data-bs-toggle="collapse" role="button" aria-expanded="{{ $isSubmittedCareer ? 'true' : 'false' }}" aria-controls="career-{{ $career->id }}">
                                        {{ __('front.read_more') }}
                                    </a>
                                </div>
                            </div>

                            <div class="collapse {{ $isSubmittedCareer ? 'show' : '' }}" id="career-{{ $career->id }}">
                                <div class="pt-3 border-top">
                                    <div class="mb-3">{!! localized_value($career, 'content') !!}</div>
                                    @if($isOpen)
                                        <div class="bg-light rounded-3 border p-4 mt-4">
                                            <div class="mb-4">
                                                <h5 class="mb-2">{{ __('front.career_apply_title') }}</h5>
                                                <p class="text-muted mb-0">{{ __('front.career_apply_note') }}</p>
                                            </div>

                                            <form method="POST" action="{{ route('landing.career.apply') }}" enctype="multipart/form-data" class="row g-3">
                                                @csrf
                                                <input type="hidden" name="career_id" value="{{ $career->id }}">

                                                <div class="col-md-6">
                                                    <label for="full_name_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_full_name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" id="full_name_{{ $career->id }}" name="full_name" class="form-control {{ $isSubmittedCareer && $errors->has('full_name') ? 'is-invalid' : '' }}" value="{{ $isSubmittedCareer ? old('full_name') : '' }}" required>
                                                    @if($isSubmittedCareer && $errors->has('full_name'))
                                                        <div class="invalid-feedback">{{ $errors->first('full_name') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="email_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_email') }} <span class="text-danger">*</span></label>
                                                    <input type="email" id="email_{{ $career->id }}" name="email" class="form-control {{ $isSubmittedCareer && $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $isSubmittedCareer ? old('email') : '' }}" required>
                                                    @if($isSubmittedCareer && $errors->has('email'))
                                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="phone_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_phone') }} <span class="text-danger">*</span></label>
                                                    <input type="text" id="phone_{{ $career->id }}" name="phone" class="form-control {{ $isSubmittedCareer && $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ $isSubmittedCareer ? old('phone') : '' }}" required>
                                                    @if($isSubmittedCareer && $errors->has('phone'))
                                                        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="latest_education_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_latest_education') }}</label>
                                                    <input type="text" id="latest_education_{{ $career->id }}" name="latest_education" class="form-control {{ $isSubmittedCareer && $errors->has('latest_education') ? 'is-invalid' : '' }}" value="{{ $isSubmittedCareer ? old('latest_education') : '' }}" placeholder="{{ __('front.career_latest_education_placeholder') }}">
                                                    @if($isSubmittedCareer && $errors->has('latest_education'))
                                                        <div class="invalid-feedback">{{ $errors->first('latest_education') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="cv_file_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_cv_file') }}</label>
                                                    <input type="file" id="cv_file_{{ $career->id }}" name="cv_file" class="form-control {{ $isSubmittedCareer && $errors->has('cv_file') ? 'is-invalid' : '' }}" accept=".pdf,.doc,.docx">
                                                    <div class="form-text">{{ __('front.career_cv_file_help') }}</div>
                                                    @if($isSubmittedCareer && $errors->has('cv_file'))
                                                        <div class="invalid-feedback">{{ $errors->first('cv_file') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="portfolio_url_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_portfolio_url') }}</label>
                                                    <input type="url" id="portfolio_url_{{ $career->id }}" name="portfolio_url" class="form-control {{ $isSubmittedCareer && $errors->has('portfolio_url') ? 'is-invalid' : '' }}" value="{{ $isSubmittedCareer ? old('portfolio_url') : '' }}" placeholder="https://">
                                                    @if($isSubmittedCareer && $errors->has('portfolio_url'))
                                                        <div class="invalid-feedback">{{ $errors->first('portfolio_url') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-12">
                                                    <label for="cover_message_{{ $career->id }}" class="form-label fw-semibold">{{ __('front.career_cover_message') }}</label>
                                                    <textarea id="cover_message_{{ $career->id }}" name="cover_message" rows="4" class="form-control {{ $isSubmittedCareer && $errors->has('cover_message') ? 'is-invalid' : '' }}" placeholder="{{ __('front.career_cover_message_placeholder') }}">{{ $isSubmittedCareer ? old('cover_message') : '' }}</textarea>
                                                    @if($isSubmittedCareer && $errors->has('cover_message'))
                                                        <div class="invalid-feedback">{{ $errors->first('cover_message') }}</div>
                                                    @endif
                                                </div>

                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">{{ __('front.career_submit_application') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-light border mb-0">{{ __('front.career_closed_note') }}</div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_careers') }}</div>
                    </div>
                @endforelse
            </div>

            @if($careers->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $careers->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
