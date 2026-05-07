@extends('layouts.front')

@section('landing')
    @php
        $breadcrumbImageUrl = !empty($category->img)
            ? asset('images/gallery/' . $category->img)
            : null;
    @endphp

    <div class="container-fluid bg-breadcrumb {{ $breadcrumbImageUrl ? 'bg-breadcrumb-dynamic' : '' }}">
        @if($breadcrumbImageUrl)
            <img src="{{ $breadcrumbImageUrl }}" alt="" class="breadcrumb-bg-image" aria-hidden="true">
        @endif
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ localized_value($category, 'title', __('front.gallery')) }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('landing.gallery') }}">{{ __('front.gallery') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ localized_value($category, 'title', __('front.gallery')) }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-8">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.gallery') }}</h4>
                        <h1 class="display-4 mb-3">{{ $pageTitle }}</h1>
                        <p class="mb-0">{{ $pageDesc }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-lg-end wow fadeInUp" data-wow-delay="0.25s">
                        <a href="{{ route('landing.gallery') }}" class="btn btn-outline-primary rounded-pill px-4 py-3">
                            <i class="fa fa-arrow-left me-2"></i>{{ __('front.back_to_gallery') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                @forelse($items as $index => $item)
                    @php
                        $imageUrl = $item->img ? asset('images/gallery/detail/' . $item->img) : asset('landing/img/blog-1.png');
                    @endphp
                    <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.08), 2) }}s">
                        <article class="bg-white rounded shadow-sm h-100 overflow-hidden">
                            <a href="{{ $imageUrl }}" data-lightbox="gallery-category" data-title="{{ localized_value($item, 'title') }}" class="d-block">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ localized_value($item, 'title') }}"
                                    class="w-100 landing-card-image"
                                >
                            </a>
                            <div class="p-4">
                                <h5 class="mb-2">{{ localized_value($item, 'title') }}</h5>
                                <p class="text-muted mb-0">{{ localized_value($category, 'title', __('front.gallery')) }}</p>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_gallery_items') }}</div>
                    </div>
                @endforelse
            </div>

            @if($items->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $items->links('components.front-pagination') }}
                </div>
            @endif

            @if($relatedCategories->isNotEmpty())
                <div class="pt-5 border-top">
                    <div class="d-flex justify-content-between align-items-end gap-3 flex-wrap mb-4">
                        <div>
                            <h4 class="text-primary mb-2">{{ __('front.other_gallery_categories') }}</h4>
                            <h2 class="mb-0">{{ __('front.explore_other_gallery_categories') }}</h2>
                        </div>
                    </div>
                    <div class="row g-4">
                        @foreach($relatedCategories as $relatedCategory)
                            @php
                                $relatedCover = $relatedCategory->galleries->first();
                                $relatedImageUrl = $relatedCover?->img ? asset('images/gallery/detail/' . $relatedCover->img) : asset('landing/img/blog-1.png');
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <article class="bg-white rounded shadow-sm h-100 overflow-hidden">
                                    <a href="{{ route('landing.gallery.show', $relatedCategory->slug) }}" class="d-block">
                                        <img
                                            src="{{ $relatedImageUrl }}"
                                            alt="{{ localized_value($relatedCategory, 'title') }}"
                                            class="w-100 gallery-detail-image"
                                        >
                                    </a>
                                    <div class="p-4">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                            <h5 class="mb-0">{{ localized_value($relatedCategory, 'title') }}</h5>
                                            <small class="text-muted">{{ __('front.gallery_total_photos', ['count' => $relatedCategory->galleries_count]) }}</small>
                                        </div>
                                        <p class="text-muted mb-4">{{ $relatedCategory->location ?: __('front.gallery_content_empty') }}</p>
                                        <a href="{{ route('landing.gallery.show', $relatedCategory->slug) }}" class="btn p-0">{{ __('front.view_category_gallery') }} <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
