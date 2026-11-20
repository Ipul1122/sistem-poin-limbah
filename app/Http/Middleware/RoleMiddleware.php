<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            // Jika peserta mencoba mengakses halaman admin atau sebaliknya
            if ($user->role === 'peserta') {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin Administrator untuk mengakses halaman ini.');
            } else {
                abort(403, 'Akses Ditolak: Halaman ini khusus untuk akun Peserta.');
            }
        }

        return $next($request);
    }
}
