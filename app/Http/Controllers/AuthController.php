<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Invitation;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->is_superadmin) {
                return redirect('/dashboard');
            }

            $companies = $user->companies;

            if ($companies->count() == 0) {
                Auth::logout();
                return back()->with('error', 'No company assigned');
            }

            if ($companies->count() >= 1) {
                session(['current_company_id' => $companies[0]->id]);
                session(['currentUserRole' => $companies[0]->pivot->role]);
                return redirect('/dashboard');
            }
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    // public function form($token)
    // {
    //     $invite = Invitation::where('token', $token)
    //         ->where('status', 'pending')
    //         ->first();

    //     if (!$invite) {
    //         return view('error', [
    //             'title' => 'Invalid or Expired Invite',
    //             'message' => 'This invite link is either invalid or already used.'
    //         ]);
    //     }

    //     $company = Company::find($invite->company_id);

    //     if (!$invite) {
    //         return redirect('/login')->with('error', 'Invalid invite token');
    //     }

    //     return view('auth.register', compact('invite', 'company'));
    // }

    public function form($token)
    {
        $invite = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invite) {
            return view('error', [
                'title' => 'Invalid or Expired Invite',
                'message' => 'This invite link is either invalid or already used.'
            ]);
        }

        $company = Company::find($invite->company_id);

        $user = null;

        if (!empty($invite->user_id)) {
            $user = User::find($invite->user_id);
            return view('auth.invitation_accepted', compact('invite', 'company', 'user'));
        }


        return view('auth.register', compact('invite', 'company'));
    }



    public function register(Request $request, $token)
    {
        try {

            $invite = Invitation::where('token', $token)->first();

            if (!$invite || $invite->status !== 'pending') {
                return view('invite-expired');
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required',
                'password' => 'required|min:6',
            ]);

       

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
            ]);

            $user->companies()->attach($invite->company_id, [
                'role' => $invite->role
            ]);

            $invite->status = 'accepted';
            $invite->user_id = $user->id;
            $invite->save();

            DB::commit();

            // dd("User registered and added to company successfully");

            return redirect('/login')
                ->with('success', 'Account created successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Invite Register Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong, please try again.');
        }
    }

    public function joinCompany(Request $request, $token)
    {
        try {

            $invite = Invitation::where('token', $token)->first();

            if (!$invite || $invite->status !== 'pending') {
                return view('invite-expired');
            }

            DB::beginTransaction();

            $user = User::find($invite->user_id);

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'User not found for this invitation.');
            }

            $role = strtolower(trim($invite->role));

            if (!in_array($role, ['admin', 'member'])) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Invalid role: ' . $invite->role);
            }

            CompanyUser::create([
                'user_id' => $user->id,
                'company_id' => $invite->company_id,
                'role' => $role,
            ]);

            $invite->status = 'accepted';
            $invite->save();

            DB::commit();

            return redirect('/login')
                ->with('success', 'Joined successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Join Company Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

}
