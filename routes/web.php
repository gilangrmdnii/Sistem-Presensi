<?php

use App\Helpers;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AtasanAttendanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', fn () => redirect('/login'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {

    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->isHrd) return redirect('/hrd/dashboard');
        if ($user->isAtasanDivisi) return redirect('/atasan/leave-approvals');
        return app(HomeController::class)(request());
    })->name('home');

    // Profile (override Jetstream default)
    Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::delete('/user/profile-photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // ===== KARYAWAN =====
    Route::middleware('user')->group(function () {
        Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/history', fn () => view('attendances.history'))->name('attendance-history');
    });

    // ===== LEAVE REQUESTS (karyawan) =====
    Route::middleware('user')->group(function () {
        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');
    });

    // ===== ATASAN DIVISI =====
    Route::middleware('atasan')->group(function () {
        Route::get('/leave-approvals', [LeaveApprovalController::class, 'index'])->name('leave-approvals.index');
        Route::get('/leave-approvals/{leaveRequest}', [LeaveApprovalController::class, 'show'])->name('leave-approvals.show');
        Route::post('/leave-approvals/{leaveRequest}/approve', [LeaveApprovalController::class, 'approve'])->name('leave-approvals.approve');
        Route::post('/leave-approvals/{leaveRequest}/reject', [LeaveApprovalController::class, 'reject'])->name('leave-approvals.reject');

        Route::get('/atasan/attendances', [AtasanAttendanceController::class, 'index'])->name('atasan.attendances');
    });

    // ===== HRD =====
    Route::middleware('hrd')->prefix('hrd')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');

        // Karyawan
        Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

        // Presensi
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('admin.attendances');
        Route::get('/attendances/report', [AdminAttendanceController::class, 'report'])->name('admin.attendances.report');

        // QR Code (stored as "barcodes" table, but UI label = "QR Code")
        Route::resource('/barcodes', BarcodeController::class)
            ->only(['index', 'create', 'store', 'edit', 'update'])
            ->names([
                'index' => 'admin.barcodes',
                'create' => 'admin.barcodes.create',
                'store' => 'admin.barcodes.store',
                'edit' => 'admin.barcodes.edit',
                'update' => 'admin.barcodes.update',
            ]);
        Route::get('/barcodes/download/all', [BarcodeController::class, 'downloadAll'])->name('admin.barcodes.downloadall');
        Route::get('/barcodes/{id}/download', [BarcodeController::class, 'download'])->name('admin.barcodes.download');

        // Master Data
        Route::match(['get', 'post', 'delete'], '/master/division', [MasterDataController::class, 'division'])->name('admin.masters.division');
        Route::match(['get', 'post', 'delete'], '/master/job-title', [MasterDataController::class, 'jobTitle'])->name('admin.masters.job-title');
        Route::match(['get', 'post', 'delete'], '/master/education', [MasterDataController::class, 'education'])->name('admin.masters.education');
        Route::get('/master/admin', [MasterDataController::class, 'admin'])->name('admin.masters.admin');

        // Pengaturan sistem (jam kerja, lokasi kantor, dll)
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Import/Export
        Route::get('/import-export/users', [ImportExportController::class, 'users'])->name('admin.import-export.users');
        Route::get('/import-export/attendances', [ImportExportController::class, 'attendances'])->name('admin.import-export.attendances');
        Route::post('/users/import', [ImportExportController::class, 'importUsers'])->name('admin.users.import');
        Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])->name('admin.attendances.import');
        Route::get('/users/export', [ImportExportController::class, 'exportUsers'])->name('admin.users.export');
        Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])->name('admin.attendances.export');
    });
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(Helpers::getNonRootBaseUrlPath() . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    $path = config('app.debug') ? '/livewire/livewire.js' : '/livewire/livewire.min.js';
    return Route::get(url($path), $handle);
});
