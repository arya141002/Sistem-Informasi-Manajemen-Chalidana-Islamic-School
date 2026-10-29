<div>
    <h5 class="card-title">{{ __('Profile Information') }}</h5>
    <p class="text-muted small mb-3">{{ __("Update your account's profile information and email address.") }}</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="col-12">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3 col-12">
            <label class="form-label">{{ __('Current Avatar') }}</label>
            <div>
                @if($user->avatar)
                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="avatar" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover;" />
                @else
                    <img src="{{ webp_asset('assets/img/avatars/1.png') }}" alt="avatar" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover;" />
                @endif
            </div>
        </div>

        <div class="col-md-9 col-12">
            <label for="avatar" class="form-label">{{ __('Change Avatar') }}</label>
            <input id="avatar" name="avatar" type="file" class="form-control" accept="image/*" />
            @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            <div class="form-text">Max 2MB. Formats: jpg, png, webp, gif.</div>
        </div>

        <div class="col-12">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted mb-1">{{ __('Your email address is unverified.') }}</p>
                    <button form="send-verification" class="btn btn-link p-0">{{ __('Click here to re-send the verification email.') }}</button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 py-1">{{ __('A new verification link has been sent to your email address.') }}</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="badge bg-success ms-2">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</div>
