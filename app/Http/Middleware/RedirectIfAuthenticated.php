<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect berdasarkan role
                return match ($user->role) {
                    'admin_aplikasi'  => redirect()->route('admin.dashboard'),
                    'admin_desa'      => redirect()->route('desa.dashboard'),
                    'admin_kecamatan' => redirect()->route('kecamatan.dashboard'),
                    'warga'           => $next($request), // Warga tidak akses web
                    default           => $this->handleInvalidRole($request, $guard),
                };
            }
        }

        return $next($request);
    }

    /**
     * Handle user dengan role tidak valid - logout dan redirect ke login
     */
    private function handleInvalidRole(Request $request, ?string $guard): Response
    {
        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('error', 'Role tidak valid. Silakan login kembali.');
    }
}
