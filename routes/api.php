<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Santri;
use App\Models\Pembayaran;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/invoice/{nomor}', function ($nomor) {
    $santri = Santri::with(['gelombang', 'orangTua', 'pembayaran'])->where('nomor_pendaftaran', $nomor)->first();
    if (!$santri || !$santri->pembayaran) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
    $pembayaran = $santri->pembayaran;
    $gelombang = $santri->gelombang;
    return response()->json([
        'nomor_pendaftaran' => $santri->nomor_pendaftaran,
        'santri_nama' => $santri->nama,
        'santri_unit' => $santri->unit,
        'santri_alamat' => $santri->orangTua->alamat ?? '-',
        'status_administrasi' => $pembayaran->status_administrasi,
        'status_daftar_ulang' => $pembayaran->status_daftar_ulang,
        'gelombang' => [
            'biaya_administrasi' => $gelombang->biaya_administrasi ?? 0,
            'biaya_daftar_ulang' => $gelombang->biaya_daftar_ulang ?? 0,
        ],
    ]);
});

Route::get('gelombang', [GelombangController::class, 'apiIndex']);
Route::post('gelombang', [GelombangController::class, 'apiStore']);
Route::get('gelombang/{gelombang}', [GelombangController::class, 'apiShow']);
Route::put('gelombang/{gelombang}', [GelombangController::class, 'apiUpdate']);
Route::patch('gelombang/{gelombang}', [GelombangController::class, 'apiUpdate']);
Route::delete('gelombang/{gelombang}', [GelombangController::class, 'apiDestroy']);

Route::get('/admin/dashboard', [DashboardController::class, 'getData']);

// Admin API Routes
Route::prefix('admin')->middleware(['auth', 'role:super admin|admin'])->group(function () {
    Route::get('/users-with-roles', [App\Http\Controllers\Admin\RoleController::class, 'getUsersWithRoles']);
    Route::get('/roles', [App\Http\Controllers\Admin\RoleController::class, 'getRoles']);
});

Route::middleware('auth')->group(function () {
    Route::post('/user/profile', [\App\Http\Controllers\Auth\LoginController::class, 'updateProfile']);
});

// API untuk santri pembayaran
Route::get('santri/{nomor_pendaftaran}/pembayaran', function ($nomor_pendaftaran) {
    $santri = \App\Models\Santri::with(['orangTua', 'dokumen', 'pembayaran', 'gelombang'])
        ->where('nomor_pendaftaran', $nomor_pendaftaran)
        ->first();

    if (!$santri) {
        return response()->json(['error' => 'Data santri tidak ditemukan'], 404);
    }

    return response()->json([
        'santri' => $santri,
        'success' => true
    ]);
})->name('api.santri.pembayaran');

// API untuk update data santri
Route::put('santri/{nomor_pendaftaran}', function (Request $request, $nomor_pendaftaran) {
    $santri = \App\Models\Santri::where('nomor_pendaftaran', $nomor_pendaftaran)->first();

    if (!$santri) {
        return response()->json(['error' => 'Data santri tidak ditemukan'], 404);
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:255|regex:/^[A-Z]/',
        'nisn' => 'required|string|size:10|regex:/^\d{10}$/|unique:santri,nisn,' . $santri->id,
        'unit' => 'required|in:ppim,tks',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'asal_sekolah' => 'required|string|max:255',
        'anak_ke' => 'required|integer|min:1',
        'jumlah_saudara' => 'required|integer|min:1',
        'status_tes' => 'required|in:Menunggu Tes,Lulus,Tidak Lulus'
    ], [
        'nama.regex' => 'Nama harus diawali dengan huruf kapital',
        'nisn.size' => 'NISN harus tepat 10 digit',
        'nisn.regex' => 'NISN hanya boleh berisi angka',
        'nisn.unique' => 'NISN sudah terdaftar',
        'anak_ke.min' => 'Anak ke minimal 1',
        'jumlah_saudara.min' => 'Jumlah saudara minimal 1'
    ]);

    // Custom validation: anak_ke tidak boleh lebih dari jumlah_saudara
    if ($validated['anak_ke'] > $validated['jumlah_saudara']) {
        return response()->json([
            'error' => 'Anak ke tidak boleh lebih dari jumlah saudara',
            'message' => 'Anak ke tidak boleh lebih dari jumlah saudara'
        ], 422);
    }

    // Capitalize nama
    $validated['nama'] = ucwords(strtolower($validated['nama']));

    $santri->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Data santri berhasil diperbarui',
        'santri' => $santri
    ]);
})->name('api.santri.update');

// API untuk update data orang tua
Route::put('santri/{nomor_pendaftaran}/orang-tua', function (Request $request, $nomor_pendaftaran) {
    $santri = \App\Models\Santri::with('orangTua')->where('nomor_pendaftaran', $nomor_pendaftaran)->first();

    if (!$santri) {
        return response()->json(['error' => 'Data santri tidak ditemukan'], 404);
    }

    $validated = $request->validate([
        'nama_ayah' => 'required|string|max:255',
        'nama_ibu' => 'required|string|max:255',
        'pekerjaan_ayah' => 'required|string|max:255',
        'pekerjaan_ibu' => 'required|string|max:255',
        'pendidikan_ayah' => 'required|string|max:50',
        'pendidikan_ibu' => 'required|string|max:50',
        'wa_ayah' => 'required|string|max:20',
        'wa_ibu' => 'required|string|max:20'
    ]);

    $santri->orangTua->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Data orang tua berhasil diperbarui',
        'orang_tua' => $santri->orangTua
    ]);
})->name('api.santri.orang-tua.update');

// API untuk update data alamat
Route::put('santri/{nomor_pendaftaran}/alamat', function (Request $request, $nomor_pendaftaran) {
    $santri = \App\Models\Santri::with('orangTua')->where('nomor_pendaftaran', $nomor_pendaftaran)->first();

    if (!$santri) {
        return response()->json(['error' => 'Data santri tidak ditemukan'], 404);
    }

    // Debug: log data yang diterima
    \Log::info('Data alamat yang diterima:', $request->all());

    $validated = $request->validate([
        'alamat' => 'required|string',
        'provinsi_id' => 'required|string',
        'provinsi_nama' => 'nullable|string',
        'kabupaten_id' => 'required|string',
        'kabupaten_nama' => 'nullable|string',
        'kecamatan_id' => 'required|string',
        'kecamatan_nama' => 'nullable|string',
        'desa_id' => 'required|string',
        'desa_nama' => 'nullable|string',
        'kode_pos' => 'nullable|string|max:10'
    ]);

    $santri->orangTua->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Data alamat berhasil diperbarui',
        'orang_tua' => $santri->orangTua
    ]);
})->name('api.santri.alamat.update');
