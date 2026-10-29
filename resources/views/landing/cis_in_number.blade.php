@extends('layouts.front')
@section('landing')

    <div class="container-fluid bg-breadcrumb bg-breadcrumb-cis-number">
        <div class="container page-title-container py-5">
            <h4 class="text-white display-4 mb-0 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.cis_in_numbers') }}</h4>
            <ol class="breadcrumb mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.cis_in_numbers') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-stretch mb-5">
                <div class="col-lg-12 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="cis-number-hero-card p-5 h-100">
                        <h4 class="mb-3">{{ __('front.cis_in_numbers') }}</h4>
                        <div class="cis-number-copy text-white">
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
                                    <img src="{{ webp_asset('images/icon/' . $item->icon) }}" alt="{{ localized_value($item, 'title', 'CIS in Numbers') }}">
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
