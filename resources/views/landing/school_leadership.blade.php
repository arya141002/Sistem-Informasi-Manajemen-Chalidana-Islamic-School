@extends('layouts.front')
@section('landing')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.school_leadership') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.school_leadership') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid school-history-page py-5">
        <div class="container py-5">
            <section class="foundation-structure-section">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <span class="school-history-kicker foundation-structure-kicker">{{ __('front.foundation_structure') }}</span>
                    <h2 class="display-6 mt-3 mb-3">{{ __('front.school_leadership') }}</h2>
                    <p class="foundation-structure-desc mx-auto mb-0">{{ __('front.school_leadership_desc') }}</p>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach($foundationStructures as $index => $structure)
                        <div class="col-sm-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ 0.15 + ($index * 0.05) }}s">
                            <article class="foundation-structure-card h-100">
                                <div class="foundation-structure-photo">
                                    <img src="{{ $structure['image'] }}" alt="{{ $structure['name'] }}">
                                </div>
                                <div class="foundation-structure-body">
                                    <h3>{{ $structure['name'] }}</h3>
                                    <p class="foundation-structure-organization">{{ __($structure['organization_key']) }}</p>
                                    <p class="foundation-structure-position">{{ __($structure['position_key']) }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection
