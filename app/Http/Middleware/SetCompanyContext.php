<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetCompanyContext
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        if ($user->is_superadmin) {
            app()->instance('currentUserRole', 'superadmin');
            app()->instance('currentCompanyId', null);
            return $next($request);
        }

        $companyId = session('current_company_id');

        if (!$companyId) {
            return redirect('/select-company');
        }

        $role = DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->value('role');

        if (!$role) {
            abort(403, 'Unauthorized');
        }

        app()->instance('currentUserRole', $role);
        app()->instance('currentCompanyId', $companyId);

        return $next($request);
    }
}