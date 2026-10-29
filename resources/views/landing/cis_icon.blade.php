@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb bg-breadcrumb-cis-icon">
        <div class="container page-title-corner page-title-container text-start">
            <h4 class="text-white display-4 mb-2 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.cis_icon') }}</h4>
            <ol class="breadcrumb d-flex justify-content-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.cis_icon') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp section-heading-wide" data-wow-delay="0.2s">
                <h4 class="text-primary">{{ __('front.cis_icon') }}</h4>
                <h1 class="display-4 mb-4">{{ __('front.cis_icon_page_title') }}</h1>
                <p class="mb-0">{{ __('front.cis_icon_page_desc') }}</p>
            </div>

            <div class="row g-4">
                @forelse($icons as $index => $icon)
                    <div class="col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.1), 1) }}s">
                        <article class="cis-icon-card h-100">
                            <div class="cis-icon-card-media">
                                <div class="cis-icon-preview-shell">
                                    <img
                                        src="{{ $icon->file ? webp_asset('images/icon/' . $icon->file) : webp_asset('landing/img/blog-1.png') }}"
                                        alt="{{ $icon->title }}"
                                    >
                                </div>
                            </div>
                            <div class="cis-icon-card-body">
                                <h4 class="mb-0">{{ $icon->title }}</h4>
                                @if(!empty($icon->file))
                                    <a href="{{ route('landing.cis-icon.download', $icon->id) }}" class="cis-icon-download">
                                        <i class="fa fa-download"></i>{{ __('front.download_icon') }}
                                    </a>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_cis_icons') }}</div>
                    </div>
                @endforelse
            </div>

            @if($icons->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $icons->links('components.front-pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
