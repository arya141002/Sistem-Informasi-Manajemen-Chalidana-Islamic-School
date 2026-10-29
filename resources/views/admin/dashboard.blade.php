@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary" id="dashboardGreetingTitle" data-user-name="{{ auth()->user()->name ?? 'Admin' }}">Welcome</h5>
                        <p class="mb-2" id="dashboardGreetingSubtitle">Loading date and time info...</p>
                        <p class="mb-4" id="dashboardWeatherInfo">Loading weather...</p>
                    </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img
                        src="{{ asset('assets/img/illustrations/man-with-laptop-light.webp') }}"
                        height="140"
                        alt="View Badge User"
                        data-app-dark-img="illustrations/man-with-laptop-dark.png"
                        data-app-light-img="illustrations/man-with-laptop-light.webp"
                        />
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img
                            src="{{ asset('assets/img/icons/unicons/chart-success.webp') }}"
                            alt="Total Users"
                            class="rounded"
                            />
                        </div>
                        <div class="dropdown">
                            <button
                            class="btn p-0"
                            type="button"
                            id="cardOpt3"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            >
                            <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                            <a class="dropdown-item" href="{{ route('user.index') }}">View All</a>
                            </div>
                        </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Users</span>
                        <h3 class="card-title mb-2">{{ $totalUsers }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-user"></i> Registered Users</small>
                    </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img
                            src="{{ asset('assets/img/icons/unicons/wallet-info.webp') }}"
                            alt="Total Teachers"
                            class="rounded"
                            />
                        </div>
                        <div class="dropdown">
                            <button
                            class="btn p-0"
                            type="button"
                            id="cardOpt6"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            >
                            <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                        </div>
                        <span>Teachers & Staff</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $totalTeachers }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-briefcase"></i> Active Staff</small>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- Total Visitor -->
            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-md-8">
                    <h5 class="card-header m-0 me-2 pb-3">Total Visitor</h5>
                    <div id="totalRevenueChart" class="px-2" data-visitor-url="{{ route('dashboard.visitor-stats') }}"></div>
                    </div>
                    <div class="col-md-4">
                    <div class="card-body pb-2">
                        <label class="form-label mb-1" for="visitorPeriod">Period</label>
                        <select id="visitorPeriod" class="form-select form-select-sm mb-2">
                            <option value="daily" selected>Daily (7 days)</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="custom">Custom Range</option>
                        </select>

                        <div id="visitorCustomRange" class="d-none">
                            <label class="form-label mb-1" for="visitorStartDate">Start Date</label>
                            <input type="date" id="visitorStartDate" class="form-control form-control-sm mb-2">
                            <label class="form-label mb-1" for="visitorEndDate">End Date</label>
                            <input type="date" id="visitorEndDate" class="form-control form-control-sm mb-2">
                        </div>

                        <button type="button" id="visitorApplyBtn" class="btn btn-sm btn-primary w-100">Apply</button>
                    </div>
                    <div class="px-4 pb-4">
                        <div class="text-muted small mb-1">Total Visitor</div>
                        <h4 class="mb-0" id="visitorTotalCount">0</h4>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!--/ Total Visitor -->
            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="row">
                <div class="col-6 mb-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/paypal.webp') }}" alt="Total Events" class="rounded" />
                        </div>
                        <div class="dropdown">
                            <button
                            class="btn p-0"
                            type="button"
                            id="cardOpt4"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            >
                            <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                        </div>
                        <span class="d-block mb-1">Events</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $totalEvents }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-calendar"></i> Active Events</small>
                    </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/cc-primary.webp') }}" alt="Total Achievements" class="rounded" />
                        </div>
                        <div class="dropdown">
                            <button
                            class="btn p-0"
                            type="button"
                            id="cardOpt1"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            >
                            <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="cardOpt1">
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Achievements</span>
                        <h3 class="card-title mb-2">{{ $totalAchievements }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-trophy"></i> Total Achievements</small>
                    </div>
                    </div>
                </div>
                <!-- </div>
            <div class="row"> -->
                <div class="col-12 mb-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                        <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                            <div class="card-title">
                            <h5 class="text-nowrap mb-2">Testimonials Report</h5>
                            <span class="badge bg-label-success rounded-pill">Recent Reviews</span>
                            </div>
                            <div class="mt-sm-auto">
                            <small class="text-success text-nowrap fw-semibold"
                                ><i class="bx bx-star"></i> {{ $totalTestimonials }} Total</small
                            >
                            <h3 class="mb-0">{{ $totalTestimonials }}</h3>
                            </div>
                        </div>
                        <div class="w-100">
                            <div style="height: 200px;" class="rounded bg-light d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bx bx-comment fs-1 text-muted"></i>
                                    <p class="text-muted small mt-2">Total Visitor Reviews</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            @if(auth()->user()->role?->name === 'managing_director')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Managing Director Dashboard</h5>
                                    <small class="text-muted">Ringkasan tugas dan laporan strategis untuk pengambilan keputusan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-md-3">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body text-center">
                                            <div class="avatar avatar-lg mx-auto mb-3 bg-label-primary text-primary rounded-circle">
                                                <i class="bx bx-briefcase fs-3"></i>
                                            </div>
                                            <h6 class="mb-1">Strategic Goals</h6>
                                            <p class="mb-0 text-muted">Pantau target sekolah dan inisiatif strategis.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-body text-center">
                                            <div class="avatar avatar-lg mx-auto mb-3 bg-label-success text-success rounded-circle">
                                                <i class="bx bx-line-chart fs-3"></i>
                                            </div>
                                            <h6 class="mb-1">Performance</h6>
                                            <p class="mb-0 text-muted">Lihat metrik kinerja teratas secara cepat.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body text-center">
                                            <div class="avatar avatar-lg mx-auto mb-3 bg-label-warning text-warning rounded-circle">
                                                <i class="bx bx-wallet fs-3"></i>
                                            </div>
                                            <h6 class="mb-1">Budget Review</h6>
                                            <p class="mb-0 text-muted">Cek persetujuan anggaran dan pengeluaran.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-body text-center">
                                            <div class="avatar avatar-lg mx-auto mb-3 bg-label-info text-info rounded-circle">
                                                <i class="bx bx-user-check fs-3"></i>
                                            </div>
                                            <h6 class="mb-1">Leadership</h6>
                                            <p class="mb-0 text-muted">Akses tim dan laporan eksekutif.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
            <!-- Admission Applications -->
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Admission Applications</h5>
                    <small class="text-muted">{{ $totalAdmissions }} Total Applications</small>
                    </div>
                    <div class="dropdown">
                    <button
                        class="btn p-0"
                        type="button"
                        id="admissionStatistics"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="admissionStatistics">
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex flex-column align-items-center gap-1">
                        <h2 class="mb-2">{{ $totalAdmissions }}</h2>
                        <span>Total Applications</span>
                    </div>
                    <div class="avatar avatar-lg bg-label-primary text-primary rounded-circle">
                        <i class="bx bx-user-plus fs-4"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!--/ Admission Applications -->

            <!-- Gallery Statistics -->
            <div class="col-md-6 col-lg-4 order-1 mb-4">
                <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Gallery & Curriculum</h5>
                    </div>
                    <div class="dropdown">
                    <button
                        class="btn p-0"
                        type="button"
                        id="galleryStatistics"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="galleryStatistics">
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-image"></i></span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                        <div class="me-2">
                        <h6 class="mb-0">Total Gallery Images</h6>
                        <small class="text-muted">Media Files</small>
                        </div>
                        <div class="user-progress">
                        <h6 class="mb-0">{{ $totalGalleries }}</h6>
                        </div>
                    </div>
                    </div>
                    <div class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-book"></i></span>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                        <div class="me-2">
                        <h6 class="mb-0">Active Curricula</h6>
                        <small class="text-muted">Study Programs</small>
                        </div>
                        <div class="user-progress">
                        <h6 class="mb-0">{{ $totalCurriculum }}</h6>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <!--/ Gallery Statistics -->

            <!-- Transactions -->
            <div class="col-md-6 col-lg-4 order-2 mb-4">
                <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Recent Testimonials</h5>
                    <div class="dropdown">
                    <button
                        class="btn p-0"
                        type="button"
                        id="testimonialID"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="testimonialID">
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                    @forelse($recentTestimonials as $testimonial)
                        <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="bx bx-user"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                            <small class="text-muted d-block mb-1">{{ $testimonial->name }}</small>
                            <h6 class="mb-0">{{ Str::limit($testimonial->title, 30) }}</h6>
                            </div>
                            <div class="user-progress">
                            <small class="fw-semibold text-muted">{{ $testimonial->created_at->format('M d') }}</small>
                            </div>
                        </div>
                        </li>
                    @empty
                        <li class="d-flex justify-content-center py-3">
                        <span class="text-muted">No testimonials yet</span>
                        </li>
                    @endforelse
                    </ul>
                </div>
                </div>
            </div>
        <!--/ Transactions -->
        </div>
    </div>
@endsection
