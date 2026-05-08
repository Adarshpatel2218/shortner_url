<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyUser;



class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        if ($user->is_superadmin) {
            $urls = ShortUrl::all();
            $users = User::Count();
            $companies = Company::Count();
            return view('dashboard.dashboard', compact('urls', 'users', 'companies'));
        }

        $companyId = session('current_company_id');

        if (!$companyId) {
            return redirect('/select-company');
        }

        $role = \DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->value('role');

        if ($role === 'admin') {
            $urls = ShortUrl::where('company_id', $companyId)->get();
            $users = CompanyUser::where('company_id', $companyId)->count();
            $companies = $user->companies()->get();
            return view('dashboard.dashboard', compact('urls' , 'users', 'companies'));
        } else {
            $urls = ShortUrl::where('company_id', $companyId)
                ->where('user_id', $user->id)
                ->get();
        }

        $companies = $user->companies()->get();

        return view('dashboard.dashboard', compact('urls', 'companies'));

        
    }

    public function usersList()
    {
        $currentUser = auth()->user();
        $companyId = session('current_company_id');

        if ($currentUser->is_superadmin) {

            $users = CompanyUser::with([
                'user',
                'company',
                'invitation.inviter'
            ])->paginate(10);

            return view('dashboard.users', compact('users'));
        }

        if (!$companyId) {
            return redirect('/dashboard');
        }

        $company = Company::find($companyId);

        if (!$company) {
            return redirect('/select-company')->withErrors('Company not found.');
        }

        $users = CompanyUser::with(['user', 'company'])
                ->where('company_id', $companyId)
                ->paginate(10);

        return view('dashboard.users', compact('users'));
    }


}
