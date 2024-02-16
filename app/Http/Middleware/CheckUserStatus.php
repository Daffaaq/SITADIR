<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $status
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        $user = Auth::user();

        if ($user && $user->status !== $status) {
            Auth::logout(); // Logout pengguna
            return redirect()->route('login')->withErrors('Akun Anda tidak aktif. Harap hubungi administrator.');
        }

        return $next($request);
    }
}
