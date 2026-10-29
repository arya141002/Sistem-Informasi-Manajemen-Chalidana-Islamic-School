<?php

namespace App\Http\Controllers;

use App\Mail\ParentRegistrationSuccessful;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class ParentAuthController extends Controller
{
    public function showLogin()
    {
        return view('parent.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans('auth.failed')]);
        }

        $request->session()->regenerate();

        if (Auth::user()->account_type !== 'parent') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => __('This login page is only for parent accounts.'),
            ]);
        }

        return redirect()->intended(route('parent.dashboard'));
    }

    public function showRegister()
    {
        return view('parent.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'account_type' => 'parent',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->sendRegistrationEmail($user);

        return redirect()->route('parent.dashboard');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()
                ->route('parent.login')
                ->withErrors(['google' => __('Google login is not configured yet.')]);
        }

        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()
                ->route('parent.login')
                ->withErrors(['google' => __('Google login is not configured yet.')]);
        }

        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere(function ($query) use ($googleUser) {
                $query->where('email', $googleUser->getEmail())
                    ->where('account_type', 'parent');
            })
            ->first();

        if (! $user && User::query()->where('email', $googleUser->getEmail())->exists()) {
            return redirect()
                ->route('parent.login')
                ->withErrors(['google' => __('This email is already used by another account type.')]);
        }

        $isNewUser = ! $user;
        $user ??= User::create([
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Parent',
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(str()->random(32)),
            'account_type' => 'parent',
            'email_verified_at' => now(),
        ]);

        $user->forceFill([
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ])->save();

        Auth::login($user, true);
        request()->session()->regenerate();
        if ($isNewUser) {
            $this->sendRegistrationEmail($user);
        }

        return redirect()->route('parent.dashboard');
    }

    private function sendRegistrationEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new ParentRegistrationSuccessful($user));
        } catch (\Throwable $exception) {
            Log::error('Parent registration email could not be sent.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('parent.login');
    }
}
