<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parent Dashboard | {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ webp_asset('images/icon/CIS_Logo.webp') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ webp_asset('images/icon/CIS_Branding_Logo.webp') }}" alt="{{ config('app.name') }}" style="max-height: 54px;">
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('parent.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="bg-white rounded shadow-sm p-4 p-lg-5">
                        @if(session('admission_success'))
                            <div class="alert alert-success">{{ session('admission_success') }}</div>
                        @endif
                        <div class="d-flex align-items-center gap-3 mb-4">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="rounded-circle" width="56" height="56" referrerpolicy="no-referrer">
                            @else
                                <span class="btn-lg-square rounded-circle bg-primary text-white">
                                    <span class="fa fa-user"></span>
                                </span>
                            @endif
                            <div>
                                <h3 class="mb-1">Welcome, {{ auth()->user()->name }}</h3>
                                <p class="text-muted mb-0">Portal pendaftaran siswa didik baru.</p>
                            </div>
                        </div>
                        <p class="mb-4">
                            Gunakan dashboard ini untuk memantau dan menyelesaikan setiap langkah admission, mulai dari informasi siswa dan data kesehatan sampai unggah dokumen dan review pendaftaran.
                        </p>
                        @if($application)
                            <div class="alert alert-light border mb-4">
                                <div><strong>Registration Code:</strong> {{ $application->registration_code }}</div>
                                <div><strong>Status:</strong> {{ ucfirst($application->status) }}</div>
                                @if($application->registration_date)
                                    <div><strong>Registration Date:</strong> {{ $application->registration_date->format('d M Y') }}</div>
                                @endif
                            </div>
                        @endif
                        <div class="d-flex flex-wrap gap-3">
                            @if(!$application || $application->status !== 'submitted')
                                <a href="{{ route('parent.application.edit') }}" class="btn btn-primary rounded-pill px-4 py-3">
                                    <span class="fa fa-file-alt me-2"></span>{{ $application ? 'Edit Admission Form' : 'Start Admission Form' }}
                                </a>
                            @else
                                <span class="btn btn-success rounded-pill px-4 py-3">
                                    <span class="fa fa-check-circle me-2"></span>Formulir Sudah Dikirim
                                </span>
                            @endif
                            @if($application && in_array($application->status, ['biodata_completed', 'health_draft'], true))
                                <a href="{{ route('parent.application.health.edit') }}" class="btn btn-outline-primary rounded-pill px-4 py-3">
                                    <span class="fa fa-heartbeat me-2"></span>Continue Health Data
                                </a>
                            @endif
                            <a href="{{ route('landing.admission') }}" class="btn btn-outline-primary rounded-pill px-4 py-3">
                                <span class="fa fa-info-circle me-2"></span>Admission Info
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('parent._admission-steps', ['application' => $application])
                </div>
            </div>
        </div>
    </main>
</body>
</html>
