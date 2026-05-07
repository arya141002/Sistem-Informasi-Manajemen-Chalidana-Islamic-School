@extends('layouts.front')

@section('landing')

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.testimonials') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.testimonials') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-wide" data-wow-delay="0.2s">
                <h4 class="text-primary">{{ __('front.testimonials') }}</h4>
                <h1 class="display-4 mb-4">{{ __('front.testimonial_page_title') }}</h1>
                <p class="mb-0">{{ __('front.testimonial_page_desc') }}</p>
            </div>

            <div class="row g-4">
                @forelse($testimonials as $index => $testimonial)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.1), 1) }}s">
                        <div class="team-item testimonial-list-card h-100">
                            <div class="team-img">
                                <img
                                    src="{{ $testimonial->images ? asset('images/testimonial/' . $testimonial->images) : asset('landing/img/team-1.jpg') }}"
                                    class="img-fluid rounded-top w-100 testimonial-list-image"
                                    alt="{{ $testimonial->student }}"
                                >
                            </div>
                            <div class="team-title p-4 h-100">
                                <div class="mb-2 text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= (int) $testimonial->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                                <h4 class="mb-1">{{ $testimonial->student }}</h4>
                                <p class="mb-2 testimonial-list-parent">
                                    <small>{{ __('front.child_of') }}</small><br>
                                    {{ $testimonial->title }}
                                </p>
                                <p class="mb-3 small testimonial-list-meta">{{ $testimonial->grade->name ?? '-' }}{{ $testimonial->campus ? ' • ' . $testimonial->campus->title : '' }}</p>
                                <p class="mb-0 testimonial-list-content">{{ $testimonial->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_testimonials') }}</div>
                    </div>
                @endforelse
            </div>

            @if($testimonials->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $testimonials->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
