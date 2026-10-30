<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ get_system_file('site_favicon', 'assets/img/favicon/favicon.ico') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="bg-white rounded shadow-sm p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <a href="{{ url('/') }}">
                                <img src="{{ webp_asset('images/icon/CIS_Branding_Logo.webp') }}" alt="{{ config('app.name') }}" style="max-height: 64px;">
                            </a>
                            <h4 class="mt-4 mb-2">Lupa Password</h4>
                            <p class="text-muted mb-0">Masukkan email untuk menerima tautan reset password.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3">Kirim Tautan Reset</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-muted">Kembali ke login admin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
