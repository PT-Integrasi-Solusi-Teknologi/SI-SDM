<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\EnsureDemoUserAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

// Root redirect to Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected Application Routes
Route::middleware(['web', EnsureDemoUserAuthenticated::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Pegawai (CRUD & Profil)
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{employee}/notes', [EmployeeController::class, 'updateNotes'])->name('employees.notes');
    Route::post('/employees/{employee}/assignments', [EmployeeController::class, 'storeAssignment'])->name('employees.assignments');

    // Organisasi & Struktur
    Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');
    Route::post('/organization/departments', [OrganizationController::class, 'storeDepartment'])->name('organization.departments.store');
    Route::put('/organization/departments/{department}', [OrganizationController::class, 'updateDepartment'])->name('organization.departments.update');
    Route::delete('/organization/departments/{department}', [OrganizationController::class, 'destroyDepartment'])->name('organization.departments.destroy');
    Route::post('/organization/positions', [OrganizationController::class, 'storePosition'])->name('organization.positions.store');
    Route::put('/organization/positions/{position}', [OrganizationController::class, 'updatePosition'])->name('organization.positions.update');
    Route::delete('/organization/positions/{position}', [OrganizationController::class, 'destroyPosition'])->name('organization.positions.destroy');

    // Kehadiran & Absensi
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Cuti & Izin
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

    // Kompetensi & Sertifikasi
    Route::get('/competencies', [CompetencyController::class, 'index'])->name('competencies.index');
    Route::post('/competencies', [CompetencyController::class, 'storeCompetency'])->name('competencies.store');
    Route::post('/competencies/assign', [CompetencyController::class, 'assignCompetency'])->name('competencies.assign');
    Route::post('/competencies/certifications', [CompetencyController::class, 'storeCertification'])->name('competencies.certifications.store');

    // Penilaian Kinerja
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::post('/performance', [PerformanceController::class, 'store'])->name('performance.store');

    // Dokumen Kepegawaian
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Laporan & Ekspor
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'printView'])->name('reports.print');

    // Pengaturan & Audit Log
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/users', [SettingController::class, 'storeUser'])->name('settings.users.store');
    Route::put('/settings/users/{user}', [SettingController::class, 'updateUser'])->name('settings.users.update');
});
