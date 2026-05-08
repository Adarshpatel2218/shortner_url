<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(401);
        }

        $role = currentUserRole();

        if ($role !== 'member') {
            abort(403, 'Member access only');
        }

        return $next($request);
    }
}