@extends('layouts.front')
@section('landing')
    @php
        $historyTitle = localized_value($schoolHistory, 'title', __('front.school_history'));
        $historyContent = localized_value($schoolHistory, 'content', '');
        $historyPlain = trim(strip_tags($historyContent));
        $historyIntro = \Illuminate\Support\Str::limit($historyPlain, 280);
    @endphp

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5 page-title-container">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.school_history') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ __('front.about_school') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.school_history') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid school-history-page py-5">
        <div class="container py-5">
            @if($schoolHistory)
                <section class="school-history-body mt-5">
                    <div class="row g-5">
                        <div class="col-lg-4 col-xl-3">
                            <aside class="school-history-nav wow fadeInUp" data-wow-delay="0.2s">
                                <span class="school-history-kicker">{{ __('front.school_history') }}</span>
                                <h2 class="mb-3">{{ __('front.school_history_timeline') }}</h2>
                            </aside>
                        </div>
                        <div class="col-lg-8 col-xl-9">
                            <article class="school-history-article wow fadeInUp" data-wow-delay="0.3s">
                                <div class="school-history-richtext">
                                    {!! $historyContent !!}
                                </div>
                            </article>
                        </div>
                    </div>
                </section>
            @else
                <div class="alert alert-light border mb-0 text-center">
                    {{ __('front.about_ids_missing', ['ids' => 'school history']) }}
                </div>
            @endif
        </div>
    </div>
@endsection
