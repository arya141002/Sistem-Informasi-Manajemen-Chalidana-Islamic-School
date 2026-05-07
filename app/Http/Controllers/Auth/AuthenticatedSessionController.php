<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.log-in');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user()->load('role');

        if (! $user->role) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum memiliki role. Hubungi administrator.',
            ], 403);
        }

        $redirect = $this->redirectByRole($user->role->name);

        $request->session()->regenerate();
        $request->session()->put('user_role', $user->role->name);

        return response()->json([
            'success' => true,
            'redirect' => $redirect,
            'message' => 'Login successful',
            'role' => $user->role->name,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectByRole(string $roleName): string
    {
        return match ($roleName) {
            'super_admin',
            'managing_director',
            'administration_staff',
            'teacher',
            'principal' => route('dashboard'),
            default => route('dashboard'),
        };
    }
}
