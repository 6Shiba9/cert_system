<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;

// PUBLIC ROUTES
Route::get('/', function () {
    return view('certificate.access');
});

// Certificate access routes (public)
Route::get('/certificate', [CertificateController::class, 'showCertificateForm'])->name('certificate-form');
Route::post('/certificate/access', [CertificateController::class, 'accessCertificate'])->name('access-certificate');
Route::get('/certificate/download/{token}', [CertificateController::class, 'downloadCertificate'])->name('download-certificate');

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// AUTHENTICATED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin,manager')->group(function () {    
        // manager route
        Route::get('/manager', function () {return view('manage.manager');})->name('manager');

        // Activity management routes
        Route::get('/add-activity', [ActivityController::class, 'showCreateActivity'])->name('add-activity');
        Route::post('/save-activity', [ActivityController::class, 'saveActivity'])->name('save-activity');
        Route::get('/manage-activities', [ActivityController::class, 'showManageActivities'])->name('manage-activities');
        Route::get('/edit-activity/{id}', [ActivityController::class, 'editActivity'])->name('edit-activity');
        Route::put('/update-activity/{id}', [ActivityController::class, 'updateActivity'])->name('update-activity');
        Route::delete('/delete-activity/{id}', [ActivityController::class, 'deleteActivity'])->name('delete-activity');
        Route::post('/activities/{id}/upload-participants', [ActivityController::class, 'uploadParticipants'])->name('upload-participants');
        
        // Certificate management routes
        Route::get('/activities/{id}/generate-certificates', [CertificateController::class, 'generateCertificates'])->name('generate-certificates');
        Route::get('/activities/{id}/view-certificates', [CertificateController::class, 'viewCertificates'])->name('view-certificates');
        Route::get('/activities/{id}/download-all-certificates', [CertificateController::class, 'downloadAllCertificates'])->name('download-all-certificates');
        Route::get('/activities/{id}/preview-certificate', [CertificateController::class, 'previewCertificate'])->name('preview-certificate');
        Route::get('/activities/{id}/certificates', [CertificateController::class, 'showActivityCertificates'])->name('activity-certificates');
        
        // API routes for dynamic dropdowns
        Route::get('/api/branches/{agencyId}', [ActivityController::class, 'getBranchesByAgency']);
        
        // Dashboard and reports
        Route::get('/summary', [DashboardController::class, 'showSummary'])->name('summary');
        Route::get('/activity-details/{id}', [DashboardController::class, 'showActivityDetails'])->name('activity-details');
        Route::get('/export-download-log', [DashboardController::class, 'exportDownloadLog'])->name('export-download-log');
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
