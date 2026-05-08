<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
    //

    public function select()
    {
        $user = auth()->user();
        $companies = $user->companies()->get();


        return view('dashboard.selectcompany', compact('companies'));
    }

    public function setCompany(Request $request)
    {
        $request->validate([
            'company_data' => 'required'
        ]);

        [$companyId, $role] = explode('|', $request->company_data);

        session([
            'current_company_id' => $companyId,
            'currentUserRole' => $role,
        ]);

        return redirect('/dashboard');
    }


    public function index()
    {
        $companies = Company::all();

        $userCompanies = CompanyUser::with(['user', 'company'])->get();

        return view('dashboard.company.index', compact('companies', 'userCompanies'));
    }




    public function addCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        try {
            $company = Company::create([
                'name' => $request->name,
                'owner_id' => Auth::id(), 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Company added successfully!',
                'data' => $company
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
