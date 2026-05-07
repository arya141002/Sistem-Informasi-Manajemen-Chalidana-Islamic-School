@extends('layouts.front')
@section('landing')

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.cis_in_numbers') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.cis_in_numbers') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-stretch mb-5">
                <div class="col-lg-7 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="cis-number-hero-card p-5 h-100">
                        <h4 class="mb-3">{{ __('front.cis_in_numbers') }}</h4>
                        <h1 class="display-4 mb-4">{{ $settings['site_name'] ?? config('app.name') }}</h1>
                        <div class="mb-4">{{ localized_value($tagline, 'jargon', $settings['site_description'] ?? ('Website resmi ' . config('app.name'))) }}</div>
                        <div class="cis-number-big">{{ $cisInNumbers->count() }}</div>
                        <div class="mt-2">{{ __('front.cis_in_numbers') }}</div>
                    </div>
                </div>
                <div class="col-lg-5 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="cis-number-grid-card p-5 h-100">
                        <h4 class="text-primary mb-3">{{ __('front.about_school') }}</h4>
                        <h2 class="h3 mb-3">{{ __('front.cis_in_numbers') }}</h2>
                        <div class="cis-number-copy">
                            {{ __('front.cis_in_numbers_desc') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($cisInNumbers as $index => $item)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.1), 1) }}s">
                        <div class="cis-number-grid-card p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="cis-number-icon">
                                    <img src="{{ asset('images/icon/' . $item->icon) }}" alt="{{ localized_value($item, 'title', 'CIS in Numbers') }}">
                                </span>
                                <div class="cis-number-grid-value">{{ $item->number }}</div>
                            </div>
                            <h4 class="mb-3">{{ localized_value($item, 'title', '-') }}</h4>
                            <div class="cis-number-copy">{!! $item->content !!}</div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_cis_in_numbers') }}</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
