<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ShortUrlController;

// Route::get('/', function () {
//     return view('welcome');
// });

//Auth
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


// Public
Route::get('/invite/{token}', [AuthController::class, 'form']);
Route::post('/invite/{token}', [AuthController::class, 'register'])->name('register');
Route::post('/joininvite/{token}', [AuthController::class, 'joinCompany'])->name('joinregister');

// Public redirect
Route::get('/s/{code}', [ShortUrlController::class, 'redirect']);


//Protected
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:superadmin,admin,member'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
        Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');

    });

    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::get('/users', [DashboardController::class, 'usersList'])->name('users.index');
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/companies/add', [CompanyController::class, 'addCompany'])->name('companies.add');
        Route::post('/invite', [InviteController::class, 'generate']);
        Route::post('/invite-old', [InviteController::class, 'invite']);
    });

    Route::middleware(['role:admin,member'])->group(function () {
        Route::get('/select-company', [CompanyController::class, 'select']);
        Route::post('/change-company', [CompanyController::class, 'setCompany']);
        // ajax create
        Route::post('/short-urls/store', [ShortUrlController::class, 'store']);
        
    });

});