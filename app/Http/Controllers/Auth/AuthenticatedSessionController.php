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
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Generate Sanctum token untuk dashboard notification bell
        $token = $user->createToken('web-session')->plainTextToken;
        session(['api_token' => $token]);

        $destination = match ($user->role) {
            'admin_aplikasi'   => route('admin.dashboard', absolute: false),
            'admin_desa'       => route('desa.dashboard', absolute: false),
            'admin_kecamatan'  => route('kecamatan.dashboard', absolute: false),
            default            => route('login', absolute: false),
        };

        return redirect()->intended($destination);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Hapus token web-session
        if ($user) {
            $user->tokens()->where('name', 'web-session')->delete();
            $request->session()->forget('api_token');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
