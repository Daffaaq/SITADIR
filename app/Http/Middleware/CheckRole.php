<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$this->hasRequiredRole($user->role, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }

    private function hasRequiredRole($userRole, $requiredRoles)
    {
        return in_array($userRole, $requiredRoles);
    }
}
