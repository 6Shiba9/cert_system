<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES - ไม่ต้อง Login
|--------------------------------------------------------------------------
*/

// หน้าแรก - User Dashboard
Route::get('/', [CertificateController::class, 'userDashboard'])->name('user.dashboard');
// web.php - เพิ่มใน PUBLIC ROUTES
Route::post('/search-by-name', [CertificateController::class, 'searchByName'])->name('certificate.search.name');
// === วิธีที่ 1: เข้าถึงผ่านรหัสและชื่อ (Access Code + Name) ===
Route::get('/certificate/access', [CertificateController::class, 'showCertificateForm'])->name('certificate.form');
Route::post('/certificate/access', [CertificateController::class, 'accessCertificate'])->name('certificate.access');

// === วิธีที่ 2: เลือกจากรายชื่อ (Activity List) ===
Route::get('/certificate/select/{accessCode}', [CertificateController::class, 'selectParticipant'])->name('certificate.select');

// === ยืนยันรหัสนักศึกษา (ใช้ทั้ง 2 วิธี) ===
Route::get('/certificate/verify/{token}', [CertificateController::class, 'showVerifyForm'])->name('certificate.verify.form');
Route::post('/certificate/verify/{token}', [CertificateController::class, 'verifyStudent'])->name('certificate.verify');

// === ดู/ดาวน์โหลด PDF Certificate ===
Route::get('/certificate/pdf/{token}', [CertificateController::class, 'viewCertificatePdf'])->name('certificate.pdf');
Route::get('/certificate/download/{token}', [CertificateController::class, 'downloadCertificatePdf'])->name('certificate.download');
// web.php - เพิ่มใน PUBLIC ROUTES
// เพิ่มใน web.php (PUBLIC ROUTES)
Route::get('/verify-certificate/{token}', [CertificateController::class, 'verifyPublicCertificate'])
    ->name('certificate.verify.public');
/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES - ต้อง Login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | ADMIN & MANAGER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,manager')->group(function () {
        
        // ============= DASHBOARD =============
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // ============= ACTIVITY MANAGEMENT =============
        // สร้างกิจกรรม
        Route::get('/add-activity', [ActivityController::class, 'showCreateActivity'])->name('add-activity');
        Route::post('/save-activity', [ActivityController::class, 'saveActivity'])->name('save-activity');
        
        // จัดการกิจกรรม
        Route::get('/manage-activities', [ActivityController::class, 'showManageActivities'])->name('manage-activities');
        Route::get('/edit-activity/{id}', [ActivityController::class, 'editActivity'])->name('edit-activity');
        Route::put('/update-activity/{id}', [ActivityController::class, 'updateActivity'])->name('update-activity');
        Route::delete('/delete-activity/{id}', [ActivityController::class, 'deleteActivity'])->name('delete-activity');
        
        // ============= CERTIFICATE MANAGEMENT =============
        // เพิ่ม/แก้ไขใบประกาศ
        Route::get('/add-certificate/{activity_id}', [ActivityController::class, 'showAddCertificate'])->name('add-certificate');
        Route::post('/activity/store-certificate', [ActivityController::class, 'storeCertificate'])->name('activity.storeCertificate');
        
        // Preview Certificate
        Route::get('/activities/{id}/preview', [CertificateController::class, 'showPreviewCertificate'])->name('certificate.preview.page');
        Route::get('/activities/{id}/preview-certificate', [CertificateController::class, 'previewCertificate'])->name('certificate.preview');

        // Preview & Generate
        Route::get('/activities/{id}/preview-certificate', [CertificateController::class, 'previewCertificate'])->name('certificate.preview');
        Route::get('/activities/{id}/generate-certificates', [CertificateController::class, 'generateCertificates'])->name('generate-certificates');
        Route::get('/activities/{id}/view-certificates', [CertificateController::class, 'viewCertificates'])->name('view-certificates');
        Route::get('/activities/{id}/download-all-certificates', [CertificateController::class, 'downloadAllCertificates'])->name('download-all-certificates');
        
        // ============= PARTICIPANT MANAGEMENT =============
        // แสดงรายชื่อผู้เข้าร่วม
        Route::get('/activity/{id}/certificates', [ActivityController::class, 'showUploadParticipants'])->name('activity.certificates');
        
        // เพิ่ม/แก้ไข/ลบ ผู้เข้าร่วม
        Route::post('/activity/{id}/participants/add', [ActivityController::class, 'addParticipant'])->name('participant.add');
        Route::put('/participant/{id}/update', [ActivityController::class, 'updateparticipant'])->name('participant.update');
        Route::delete('/participant/{id}/delete', [ActivityController::class, 'deleteParticipant'])->name('participant.delete');
        
        // อัพโหลด Excel
        Route::post('/activity/{id}/participants/upload', [ActivityController::class, 'uploadParticipants'])->name('participants.upload');
        
        // ============= API ROUTES =============
        Route::get('/api/branches/{agencyId}', [ActivityController::class, 'getBranchesByAgency']);
        // 
       Route::get('/download-template-general', [ActivityController::class, 'downloadTemplateGeneral'])->name('download-template-general');
    });
    
    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        
        // ============= USER MANAGEMENT =============
        Route::get('/ManageUser', [ManageUserController::class, 'showmanageUser'])->name('ManageUser');
        Route::post('/createuser', [ManageUserController::class, 'createuser'])->name('createuser');
        Route::get('/edituser/{id}', [ManageUserController::class, 'edituser'])->name('edituser');
        Route::put('/updateuser/{id}', [ManageUserController::class, 'updateuser'])->name('updateuser');
        Route::delete('/deleteuser/{id}', [ManageUserController::class, 'deleteuser'])->name('deleteuser');
        
        // ============= AGENCY MANAGEMENT =============
        Route::get('/agency', [AgencyController::class, 'showagency'])->name('agency');
        Route::post('/createagency', [AgencyController::class, 'createagency'])->name('createagency');
        Route::put('/updateagency/{id}', [AgencyController::class, 'updateagency'])->name('updateagency');
        Route::delete('/deleteagency/{id}', [AgencyController::class, 'deleteagency'])->name('deleteagency');
        
        // ============= BRANCH MANAGEMENT =============
        Route::post('/createbranch', [AgencyController::class, 'createbranch'])->name('createbranch');
        Route::put('/updatebranch/{id}', [AgencyController::class, 'updatebranch'])->name('updatebranch');
        Route::delete('/deletebranch/{id}', [AgencyController::class, 'deletebranch'])->name('deletebranch');
        
        // ============= REPORTS & ANALYTICS =============
        Route::get('/summary', [DashboardController::class, 'showSummary'])->name('summary');
        Route::get('/activity-details/{id}', [DashboardController::class, 'showActivityDetails'])->name('activity-details');
        Route::get('/export-download-log', [DashboardController::class, 'exportDownloadLog'])->name('export-download-log');
    });
});