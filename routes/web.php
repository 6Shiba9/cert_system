<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ActivityController;
// PUBLIC ROUTES
Route::get('/', function () {
    return 'Welcome Page';
});
// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    
});
// Logout
route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// AUTHENTICATED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin,manager')->group(function () {    
        // manager route
        Route::get('/manager', function () {return view('manage.manager');})->name('manager');

        // addactivity route
        Route::get('/add-activity', [ActivityController::class, 'showCreateActivity'])->name('add-activity');
    });

   
    // ManageUser & Agency routes สำหรับ admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/ManageUser', [ManageUserController::class, 'showmanageUser'])->name('ManageUser');
        Route::post('/createuser', [ManageUserController::class, 'createuser'])->name('createuser');
        Route::get('/edituser/{id}', [ManageUserController::class, 'edituser'])->name('edituser');
        Route::put('/updateuser/{id}', [ManageUserController::class, 'updateuser'])->name('updateuser');
        Route::delete('/deleteuser/{id}', [ManageUserController::class, 'deleteuser'])->name('deleteuser');

        // Agency routes
        Route::get('/agency', [AgencyController::class, 'showagency'])->name('agency');
        Route::post('/createagency', [AgencyController::class, 'createagency'])->name('createagency');
        Route::put('/updateagency/{id}', [AgencyController::class, 'updateagency'])->name('updateagency');
        Route::delete('/deleteagency/{id}', [AgencyController::class, 'deleteagency'])->name('deleteagency');

        // Branch routes
        Route::post('/createbranch', [AgencyController::class, 'createbranch'])->name('createbranch');
        Route::put('/updatebranch/{id}', [AgencyController::class, 'updatebranch'])->name('updatebranch');
        Route::delete('/deletebranch/{id}', [AgencyController::class, 'deletebranch'])->name('deletebranch');
    });
});
