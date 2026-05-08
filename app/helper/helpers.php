<?php

use App\Models\CompanyUser;

function currentCompanyId()
{
    return session('current_company_id');
}

function currentUserRole()
{
    // dd(session('currentUserRole') , "from helper");
    return session('currentUserRole');
}