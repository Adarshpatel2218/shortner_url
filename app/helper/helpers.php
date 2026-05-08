<?php

use App\Models\CompanyUser;

function currentCompanyId()
{
    return session('current_company_id');
}

function currentUserRole()
{
    $user = auth()->user();

    return CompanyUser::where('user_id', $user->id)
        ->where('company_id', currentCompanyId())
        ->value('role');
}