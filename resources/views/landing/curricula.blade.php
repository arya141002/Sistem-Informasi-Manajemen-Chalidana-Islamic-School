@extends('layouts.front')

@section('landing')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.curricula') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.curricula') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-lg-8">
                    <div class="mx-auto mx-lg-0 wow fadeInUp content-heading-narrow" data-wow-delay="0.2s">
                        <h4 class="text-primary">{{ __('front.curricula') }}</h4>
                        <h1 class="display-4 mb-3">{{ __('front.curricula_page_title') }}</h1>
                        <p class="mb-0">{{ __('front.curricula_page_desc') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-lg-end wow fadeInUp" data-wow-delay="0.25s">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-4 py-3">
                            {{ __('front.curricula_total', ['count' => $curricula->count()]) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($curricula as $index => $curriculum)
                    <div class="col-12 wow fadeInUp" data-wow-delay="{{ number_format(0.2 + (($index % 6) * 0.08), 2) }}s">
                        <article class="bg-white rounded-4 shadow-sm overflow-hidden h-100">
                            <div class="p-4 p-lg-5">
                                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4 mb-4">
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-3">
                                            {{ __('front.curriculum_label') }}
                                        </span>
                                        <h2 class="mb-0">{{ localized_value($curriculum, 'title') }}</h2>
                                    </div>
                                    <a
                                        href="#curriculum-{{ $curriculum->id }}"
                                        class="btn btn-outline-primary rounded-pill px-4 py-2 flex-shrink-0"
                                        data-bs-toggle="collapse"
                                        role="button"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="curriculum-{{ $curriculum->id }}"
                                    >
                                        {{ __('front.read_more') }}
                                    </a>
                                </div>
                                <div class="collapse {{ $index === 0 ? 'show' : '' }}" id="curriculum-{{ $curriculum->id }}">
                                    <div class="pt-3 border-top">
                                        <div class="text-muted curriculum-detail-content">
                                            {!! localized_value($curriculum, 'content') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 text-center">{{ __('front.no_curricula') }}</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
