<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetCompanyContext
{
    public function handle(Request $request, Closure $next)
    {
        // 👤 agar login nahi hai
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // 🔴 SuperAdmin case
        if ($user->is_superadmin) {
            app()->instance('currentUserRole', 'superadmin');
            app()->instance('currentCompanyId', null);
            return $next($request);
        }

        // 🟢 Normal user (admin/member)
        $companyId = session('current_company_id');

        // ❌ agar company select nahi ki
        if (!$companyId) {
            return redirect('/select-company');
        }

        // 🔍 role find karo
        $role = DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->value('role');

        // ❌ invalid access
        if (!$role) {
            abort(403, 'Unauthorized');
        }

        // ✅ globally store karo
        app()->instance('currentUserRole', $role);
        app()->instance('currentCompanyId', $companyId);

        return $next($request);
    }
}