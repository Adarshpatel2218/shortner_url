<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        $currentRole = $user->is_superadmin ? 'superadmin' : session('currentUserRole');
        // dd($currentRole, $roles);

        if (! in_array($currentRole, $roles)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}