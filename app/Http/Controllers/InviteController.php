<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Support\Facades\Log;

class InviteController extends Controller
{

    public function index()
    {
        try {

            $user = auth()->user();

            // 🔴 Admin → session se company
            if (currentUserRole() == 'admin') {

                $companyId = session('current_company_id');

                if (!$companyId) {
                    return redirect('/select-company');
                }

                $invitations = Invitation::with('invitedByUser')
                    ->where('company_id', $companyId)
                    ->where('invited_by', '!=', 1)
                    ->latest()
                    ->paginate(10);

                $company = Company::find($companyId);

                return view('dashboard.invitation.index', compact('invitations', 'company'));
            }

            // 🔵 SuperAdmin → all data
            $invitations = Invitation::with('invitedByUser')
                ->latest()
                ->paginate(10);

            return view('dashboard.invitation.index', compact('invitations'));

        } catch (\Exception $e) {

            Log::error('Invitation fetch error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }


    public function generate(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'role' => 'required|in:admin,member',
        ]);

        $token = Str::random(40);

        DB::table('invitations')->insert([
            'company_id' => $request->company_id,
            'invited_by' => auth()->id(),
            'role' => $request->role,
            'token' => $token,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $link = url('/invite/' . $token);

        // Ajax ke liye JSON return karein
        return response()->json([
            'success' => true,
            'link' => $link,
            'message' => 'Invitation link generated successfully!'
        ]);
    }

    public function invite(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'role' => 'required|in:admin,member',
            'userId' => 'required',
        ]);

        $existing = DB::table('invitations')
            ->where('company_id', $request->company_id)
            ->where('user_id', $request->userId)
            ->where('role', $request->role)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This user already has a pending invite for this role in this company.'
            ]);
        }

        $token = Str::random(40);

        DB::table('invitations')->insert([
            'company_id' => $request->company_id,
            'user_id' => $request->userId,
            'invited_by' => auth()->id(),
            'role' => $request->role,
            'token' => $token,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully!',
        ]);
    }


}
