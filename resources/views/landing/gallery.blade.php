@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.gallery') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.gallery') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-9">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.gallery') }}</h4>
                        <h1 class="display-4 mb-3">{{ __('front.gallery_page_title') }}</h1>
                        <p class="mb-0">{{ __('front.gallery_page_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="text-lg-end wow fadeInUp" data-wow-delay="0.25s">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-4 py-3">
                            {{ __('front.gallery_category_count', ['count' => $categories->count()]) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($categories as $index => $category)
                    @php
                        $coverImage = $category->galleries->first();
                        $imageUrl = $coverImage?->img ? asset('images/gallery/detail/' . $coverImage->img) : asset('landing/img/blog-1.png');
                    @endphp
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.08), 2) }}s">
                        <article class="bg-white rounded shadow-sm h-100 overflow-hidden">
                            <a href="{{ route('landing.gallery.show', $category->slug) }}" class="d-block">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ localized_value($category, 'title') }}"
                                    class="w-100 gallery-category-image"
                                >
                            </a>
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                        {{ __('front.gallery') }}
                                    </span>
                                    <small class="text-muted text-end">{{ __('front.gallery_total_photos', ['count' => $category->galleries_count]) }}</small>
                                </div>
                                <h4 class="mb-2">{{ localized_value($category, 'title') }}</h4>
                                <p class="text-muted mb-4">{{ $category->location ?: __('front.gallery_content_empty') }}</p>
                                <a href="{{ route('landing.gallery.show', $category->slug) }}" class="btn p-0">{{ __('front.view_category_gallery') }} <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_gallery_items') }}</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
