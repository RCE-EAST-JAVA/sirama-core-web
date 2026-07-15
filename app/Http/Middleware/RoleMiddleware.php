<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek autentikasi
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Validasi session masih ada di database (mencegah session mismatch)
        if (config('session.driver') === 'database') {
            $sessionId = $request->session()->getId();
            $sessionExists = DB::table(config('session.table', 'sessions'))
                ->where('id', $sessionId)
                ->exists();
            
            if (!$sessionExists) {
                // Session tidak ada di DB tapi cookie masih ada - force logout
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
        }

        // Cek role
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
