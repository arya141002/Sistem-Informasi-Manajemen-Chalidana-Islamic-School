<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parent Register | {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ webp_asset('images/icon/CIS_Logo.webp') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">
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
                            <h4 class="mt-4 mb-2">Parent Registration</h4>
                            <p class="text-muted mb-0">Buat akun orang tua untuk memulai proses admission.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <a href="{{ route('parent.google.redirect') }}" class="btn btn-outline-primary w-100 rounded-pill py-3 mb-3">
                            <span class="fab fa-google me-2"></span>Register with Google
                        </a>

                        <div class="text-center text-muted small mb-3">or register with email</div>

                        <form method="POST" action="{{ route('parent.register.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Parent Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="password" aria-label="Show password" title="Show password">
                                        <span class="fas fa-eye" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="password_confirmation" aria-label="Show password" title="Show password">
                                        <span class="fas fa-eye" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3">Create Parent Account</button>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">Sudah punya akun?</span>
                            <a href="{{ route('parent.login') }}">Login parent account</a>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('landing.admission') }}" class="text-muted">Back to Admission</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            button.setAttribute('title', isHidden ? 'Hide password' : 'Show password');
            button.querySelector('span').className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    });
</script>
</html>
