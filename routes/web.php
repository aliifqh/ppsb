<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Santri\FormulirController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Santri\DashboardSantriController;
use App\Http\Controllers\Santri\SantriPrintController;
use App\Http\Controllers\Santri\SantriPembayaranController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SantriFormController;
use App\Http\Controllers\Admin\WhatsappConfigController;
use App\Http\Controllers\Admin\WhatsappTemplateController;
use App\Http\Controllers\Admin\WhatsappAdminController;
use App\Http\Controllers\Admin\WhatsappMessageController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ||||||||||||||| API Routes untuk SPA |||||||||||||||
// Routes Formulir Pendaftaran
Route::post('/formulir/store', [FormulirController::class, 'store'])->name('formulir.store');

// Custom Login Routes
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/user/profile', [LoginController::class, 'updateProfile'])->middleware('auth');

// ||||||||||||||| Routes Public Payment |||||||||||||||
Route::prefix('payment')->group(function () {
    Route::get('/biaya-pendaftaran/{nomor_pendaftaran}', [SantriPembayaranController::class, 'biayapendaftaranPublic'])
        ->name('public.biayapendaftaran');
    Route::get('/biaya-administrasi/{nomor_pendaftaran}', [SantriPembayaranController::class, 'biayaadministrasiPublic'])
        ->name('public.biayaadministrasi');
    Route::get('/daftar-ulang/{nomor_pendaftaran}', [SantriPembayaranController::class, 'daftarulangPublic'])
        ->name('public.daftarulang');
    Route::post('/upload-bukti-pendaftaran', [SantriPembayaranController::class, 'uploadBuktiPendaftaran'])
        ->name('public.upload-bukti-pendaftaran');
    Route::post('/upload-bukti-administrasi', [SantriPembayaranController::class, 'uploadBuktiAdministrasi'])
        ->name('public.upload-bukti-administrasi');
    Route::post('/upload-bukti-daftarulang', [SantriPembayaranController::class, 'uploadBuktiDaftarUlang'])
        ->name('public.upload-bukti-daftarulang');
});

// ||||||||||||||| Routes Santri |||||||||||||||
// Routes Login & Cek Status Santri
Route::get('/cek-pendaftaran', [DashboardSantriController::class, 'checkForm'])->name('santri.check.form');
Route::post('/santri/check', [DashboardSantriController::class, 'check'])->name('santri.check');
Route::post('/santri/logout', [DashboardSantriController::class, 'logout'])->name('santri.logout');

// Routes Santri (Protected)
Route::prefix('santri')->group(function () {
    // Routes yang membutuhkan nomor pendaftaran
    Route::get('/dashboard/{nomor_pendaftaran}', [DashboardSantriController::class, 'index'])->name('santri.dashboard.index');
    Route::get('/data/{nomor_pendaftaran}', [FormulirController::class, 'index'])->name('santri.data');
    Route::get('/pembayaran/{nomor_pendaftaran}', [SantriPembayaranController::class, 'index'])->name('santri.pembayaran.index');
    Route::get('/pembayaran/biayapendaftaran/{nomor_pendaftaran}', [SantriPembayaranController::class, 'biayaPendaftaran'])->name('santri.pembayaran.biayapendaftaran');
    Route::get('/pembayaran/biayaadministrasi/{nomor_pendaftaran}', [SantriPembayaranController::class, 'biayaAdministrasi'])->name('santri.pembayaran.biayaadministrasi');
    Route::get('/pembayaran/daftarulang/{nomor_pendaftaran}', [SantriPembayaranController::class, 'daftarUlang'])->name('santri.pembayaran.daftarulang');

    // Route Ujian Santri (Update dengan controller baru)
    Route::get('/ujian/{nomor_pendaftaran}', [App\Http\Controllers\SantriUjianController::class, 'index'])->name('santri.ujian.index');

    // API Routes untuk Ujian
    Route::prefix('ujian')->group(function () {
        Route::post('/update-progress', [App\Http\Controllers\SantriUjianController::class, 'updateProgress'])->name('santri.ujian.update-progress');
        Route::post('/join-jitsi', [App\Http\Controllers\SantriUjianController::class, 'joinJitsi'])->name('santri.ujian.join-jitsi');
    });

    Route::get('/print/{nomor_pendaftaran}', [SantriPrintController::class, 'index'])->name('santri.print');

    // Upload bukti pembayaran
    Route::post('/upload-bukti-pendaftaran', [SantriPembayaranController::class, 'uploadBuktiPendaftaran'])->name('santri.upload-bukti-pendaftaran');
    Route::post('/upload-bukti-administrasi', [SantriPembayaranController::class, 'uploadBuktiAdministrasi'])->name('santri.upload-bukti-administrasi');
    Route::post('/upload-bukti-daftarulang', [SantriPembayaranController::class, 'uploadBuktiDaftarUlang'])->name('santri.upload-bukti-daftarulang');
});

Route::get('/santri/{santri}/print', [SantriPrintController::class, 'print'])
    ->name('santri.print')
    ->middleware(['auth']);

// ||||||||||||||| Routes Admin |||||||||||||||
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Santri API Routes
    Route::get('/santri/data', [SantriController::class, 'getApiData'])->name('santri.data');
    Route::get('/santri/trashed/data', [SantriController::class, 'getTrashedApiData'])->name('santri.trashed.data');

    // Santri
    Route::get('/santri/export', [SantriController::class, 'export'])->name('santri.export');
    Route::post('/santri/{id}/restore', [SantriController::class, 'restore'])->name('santri.restore');
    Route::delete('/santri/{id}/force-delete', [SantriController::class, 'forceDelete'])->name('santri.force-delete');
    Route::post('/santri/bulk-delete', [SantriController::class, 'bulkDelete'])->name('santri.bulk-delete');
    Route::post('/santri/bulk-update-status', [SantriController::class, 'bulkUpdateStatus'])->name('santri.bulk-update-status');
    Route::post('/santri/bulk-restore', [SantriController::class, 'bulkRestore'])->name('santri.bulk-restore');
    Route::post('/santri/bulk-force-delete', [SantriController::class, 'bulkForceDelete'])->name('santri.bulk-force-delete');
    Route::post('/santri/{santri}/change-gelombang', [SantriController::class, 'changeGelombang'])->name('santri.changeGelombang');
    Route::post('/santri/{santri}/update-status', [SantriController::class, 'updateStatusTes'])->name('santri.update-status');
    Route::post('/santri/{id}/generate-magic-link', [SantriController::class, 'generateMagicLink'])->name('santri.generate-magic-link');
    Route::get('/santri/form', [SantriFormController::class, 'create'])->name('santri.form');
    Route::post('/santri/form', [SantriFormController::class, 'store'])->name('santri.form.store');
    Route::resource('santri', SantriController::class);

    // Pembayaran
    Route::get('/pembayaran/data', [PembayaranController::class, 'getApiData'])->name('pembayaran.data');
    Route::get('/pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
    Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{pembayaran}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{pembayaran}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::post('/pembayaran/{pembayaran}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::post('/pembayaran/{pembayaran}/batalkan-verifikasi', [PembayaranController::class, 'batalkanVerifikasi'])->name('pembayaran.unverify');
    Route::post('/pembayaran/{pembayaran}/upload-bukti', [PembayaranController::class, 'uploadBukti'])->name('pembayaran.upload.bukti');

    // Gelombang
    Route::get('/gelombang/api', [GelombangController::class, 'apiIndex'])->name('gelombang.api');
    Route::resource('gelombang', GelombangController::class);
    Route::post('/gelombang/{gelombang}/toggle-status', [GelombangController::class, 'toggleStatus'])->name('gelombang.toggle-status');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/update-user-role/{user}', [RoleController::class, 'updateUserRole'])->name('roles.update-user-role');
});

Route::get('/invoice/{nomor_pendaftaran}/{jenis}', [InvoiceController::class, 'show'])
    ->name('invoice.public.show')
    ->where('jenis', 'administrasi|daftar_ulang');

// API Routes untuk Vue (di luar group admin agar bisa diakses)
Route::prefix('api/admin')->middleware(['auth'])->group(function () {
    Route::get('/users-with-roles', [RoleController::class, 'getUsersWithRoles'])->name('api.admin.users-with-roles');
    Route::get('/roles', [RoleController::class, 'getRoles'])->name('api.admin.roles');
    Route::get('/dashboard', [DashboardController::class, 'getData'])->name('api.admin.dashboard');
    Route::get('/pembayaran', [PembayaranController::class, 'getApiData'])->name('api.admin.pembayaran.data');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('api.admin.pembayaran.store');
    Route::put('/pembayaran/{pembayaran}', [PembayaranController::class, 'update'])->name('api.admin.pembayaran.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('api.admin.roles.destroy');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('api.admin.roles.update');
    Route::post('/roles', [RoleController::class, 'store'])->name('api.admin.roles.store');
    Route::get('/permissions', [RoleController::class, 'getPermissions'])->name('api.admin.permissions');
    Route::get('/roles-permissions', [RoleController::class, 'getRolesPermissions'])->name('api.admin.roles-permissions');
});

// Magic login santri
Route::get('/santri/magic-login/{token}', [\App\Http\Controllers\Santri\MagicLoginSantriController::class, 'magicLogin'])->name('santri.magic.login');

// ================= SPA ROUTE - SEMUA REQUEST KE SPA =================
Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');

