<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Middleware\EnsureRoleIsAuthenticated;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\DashboardController;
use App\Http\Controllers\Admin\Auth\AdminOrmawaController;
use App\Http\Controllers\Admin\Auth\AdminDosenController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminDokumenController;
use App\Http\Controllers\DocumentController;

//login
Route::get('/', [LoginAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');


//ormawa
Route::middleware(['auth:ormawa'])->group(function () {
    Route::prefix('ormawa')->name('ormawa.')->group(function () {
        Route::get('/dashboard', [OrmawaController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengajuan', [OrmawaController::class, 'pengajuan'])->name('pengajuan');
        Route::post('/pengajuan/store', [OrmawaController::class, 'storePengajuan'])->name('pengajuan.store');
        Route::get('/riwayat', [OrmawaController::class, 'riwayat'])->name('riwayat');
        Route::get('/dokumen/{id}', [OrmawaController::class, 'getDokumenContent'])->name('dokumen.content');
        Route::get('/profile', [OrmawaController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [OrmawaController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [OrmawaController::class, 'updateProfile'])->name('profile.update');
        Route::post('/ormawa/profile/photo', [OrmawaController::class, 'updatePhoto'])->name('ormawa.profile.photo.update');
        Route::delete('/ormawa/profile/photo', [OrmawaController::class, 'destroyPhoto'])->name('ormawa.profile.photo.destroy');
        Route::post('/profile/photo', [OrmawaController::class, 'updatePhoto'])->name('profile.photo.update');
        Route::delete('/profile/photo', [OrmawaController::class, 'destroyPhoto'])->name('profile.photo.destroy');
        Route::post('/logout', [OrmawaController::class, 'logout'])->name('logout');
        Route::get('/dokumen/{id}', [OrmawaController::class, 'showDokumen'])->name('dokumen.show');
    });
});


//dosen
Route::middleware(['auth:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboardDosen'])->name('dashboard');
    Route::get('/buat-tanda-tangan', [DosenController::class, 'create'])->name('create');
    Route::post('/logout', [DosenController::class, 'logout'])->name('logout');

    // Perbaikan nama route riwayat
    Route::get('/riwayat', [DosenController::class, 'riwayat'])->name('riwayat');

    Route::get('/dokumen/{id}', [DosenController::class, 'showDokumen'])->name('dokumen.show');
    Route::get('/dokumen/{id}/content', [DosenController::class, 'getDokumenContent'])->name('dokumen.content');
    Route::get('/profile', [DosenController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [DosenController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [DosenController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [DosenController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [DosenController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::put('/profile/password', [DosenController::class, 'updatePassword'])->name('password.update');

    // Route untuk QR Code
    Route::get('/dokumen/{id}/generate-qr', [DosenController::class, 'generateQrCode'])
        ->name('dokumen.generateQr');
    Route::post('/dokumen/{dokumen}/save-qr-position', [DosenController::class, 'saveQrPosition'])
        ->name('dokumen.saveQrPosition');
    Route::get('/dokumen/{id}/edit-qr', [DosenController::class, 'editQrCode'])
        ->name('dokumen.editQr');

    // Verification route
    Route::get('/verify/document/{id}', [DosenController::class, 'verifyDocument'])
        ->name('verify.document');
});


//admin
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.adminDashboard');

        //ormawa
        Route::get('ormawa/index', [AdminOrmawaController::class, 'index'])->name('admin.ormawa.index');
        Route::get('ormawa/create', [AdminOrmawaController::class, 'create'])->name('admin.ormawa.create');
        Route::post('ormawa', [AdminOrmawaController::class, 'store'])->name('admin.ormawa.store');
        Route::get('ormawa/{ormawa}/edit', [AdminOrmawaController::class, 'edit'])->name('admin.ormawa.edit');
        Route::put('ormawa/{ormawa}', [AdminOrmawaController::class, 'update'])->name('admin.ormawa.update');
        Route::delete('ormawa/{ormawa}', [AdminOrmawaController::class, 'destroy'])->name('admin.ormawa.destroy');

       //dosen
        Route::get('dosen/index', [AdminDosenController::class, 'index'])->name('admin.dosen.index');
        Route::get('dosen/create', [AdminDosenController::class, 'create'])->name('admin.dosen.create');
        Route::post('dosen', [AdminDosenController::class, 'store'])->name('admin.dosen.store');
        Route::get('dosen/{dosen}/edit', [AdminDosenController::class, 'edit'])->name('admin.dosen.edit');
        Route::put('dosen/{dosen}', [AdminDosenController::class, 'update'])->name('admin.dosen.update');
        Route::delete('dosen/{dosen}', [AdminDosenController::class, 'destroy'])->name('admin.dosen.destroy');

        //dokumen
        Route::get('dokumen/index', [AdminDokumenController::class, 'index'])->name('admin.dokumen.index');
        // Route::get('dokumen/create', [AdminDokumenController::class, 'create'])->name('admin.dokumen.create');
        // Route::post('dokumen', [AdminDokumenController::class, 'store'])->name('admin.dokumen.store');
        Route::get('dokumen/{dokumen}/edit', [AdminDokumenController::class, 'edit'])->name('admin.dokumen.edit');
        Route::put('dokumen/{dokumen}', [AdminDokumenController::class, 'update'])->name('admin.dokumen.update');
        // Route::delete('dokumen/{dokumen}', [AdminDokumenController::class, 'destroy'])->name('admin.dokumen.destroy');

        Route::get('profile', [AdminLoginController::class, 'showProfile'])->name('admin.profile');
        Route::put('profile/update', [AdminLoginController::class, 'updateProfile'])->name('admin.profile.update');
        Route::put('profile/password', [AdminLoginController::class, 'updatePassword'])->name('admin.password.update');
        Route::get('/profile/edit', [AdminLoginController::class, 'editProfile'])->name('admin.profile.edit');
        Route::get('dokumen/{id}', [AdminDokumenController::class, 'show']);
        Route::get('dokumen/{id}/download', [AdminDokumenController::class, 'download']);
        Route::get('dokumen/{id}/view', [AdminDokumenController::class, 'view']);

        // Profile routes
        Route::get('/profile', [AdminOrmawaController::class, 'editProfile'])->name('admin.profile.edit');
        Route::put('/profile', [AdminOrmawaController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/profile/photo', [AdminOrmawaController::class, 'updateProfilePhoto'])->name('admin.profile.photo.update');
        Route::delete('/profile/photo', [AdminOrmawaController::class, 'destroyProfilePhoto'])->name('admin.profile.photo.destroy');
    });
});

// Tambahkan route ini di luar group middleware
Route::get('/verify/document/{id}', [DosenController::class, 'verifyDocument'])
    ->name('verify.document');

Route::get('/verify/document/{id}/{kode?}', [DosenController::class, 'verifyDocument'])
    ->name('verify.document');

Route::get('/dosen/dokumen/{document}/generate-qr', [DocumentController::class, 'generateQrCode'])
    ->name('dosen.dokumen.generate-qr');

Route::get('/view-document/{id}', [DocumentController::class, 'viewDocument'])->name('view.document');