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
            'company_id' => 'required|exists:companies,id'
        ]);

        session(['current_company_id' => $request->company_id]);

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
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        try {
            // 2. Create Company
            $company = Company::create([
                'name' => $request->name,
                'owner_id' => Auth::id(), // Ya auth()->id()
            ]);

            // 3. Return Success Response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Company added successfully!',
                'data' => $company
            ], 201);

        } catch (\Exception $e) {
            // 4. Return Error Response
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
