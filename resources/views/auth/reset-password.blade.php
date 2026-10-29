<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ webp_asset('images/icon/CIS_Logo.webp') }}">
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
                            <h4 class="mt-4 mb-2">Reset Password</h4>
                            <p class="text-muted mb-0">Buat password baru untuk akun Anda.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="password" aria-label="Show password" title="Show password"><span class="fas fa-eye" aria-hidden="true"></span></button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" data-password-toggle="password_confirmation" aria-label="Show password" title="Show password"><span class="fas fa-eye" aria-hidden="true"></span></button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3">Simpan Password Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
</body>
</html>
