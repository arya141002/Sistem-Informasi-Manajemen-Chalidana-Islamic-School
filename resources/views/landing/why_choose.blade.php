@extends('layouts.front')
@section('landing')

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.why_choose_cis') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.why_choose_cis') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-wide" data-wow-delay="0.2s">
                <h1 class="display-4 mb-4">{{ localized_value($whyChoose, 'title', __('front.why_choose_cis')) }}</h1>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-xl-12 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="why-choose-panel why-choose-main p-5 h-100">
                        <div class="why-choose-copy">{!! localized_value($whyChoose, 'content', e($settings['site_description'] ?? ('Website resmi ' . config('app.name')))) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
