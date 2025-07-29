<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;

class DashboardSantriController extends Controller
{
    /**
     * Menampilkan form login santri
     */
    public function checkForm()
    {
        // Jika sudah login (ada session nomor_pendaftaran), redirect ke dashboard
        if (session()->has('nomor_pendaftaran')) {
            return redirect()->route('santri.dashboard.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]);
        }
        return view('santri.auth.login');
    }

    /**
     * Memproses login santri
     */
    public function check(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|size:4',
            'nama' => 'required|string'
        ], [
            'kode.required' => 'Kode unik harus diisi',
            'kode.size' => 'Kode unik harus 4 karakter',
            'nama.required' => 'Nama lengkap harus diisi'
        ]);

        // Cari pendaftaran berdasarkan kode unik dan nama
        $santri = Santri::where('nomor_pendaftaran', 'like', '%' . $request->kode)
            ->where('nama', $request->nama)
            ->first();

        if (!$santri) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan. Pastikan kode unik dan nama lengkap sudah benar.');
        }

        // Simpan data santri ke session
        session([
            'nomor_pendaftaran' => $santri->nomor_pendaftaran,
            'nama_santri' => $santri->nama,
            'is_santri_logged_in' => true
        ]);

        return redirect()->route('santri.dashboard.index', ['nomor_pendaftaran' => $santri->nomor_pendaftaran]);
    }

    /**
     * Menampilkan dashboard santri
     */
    public function index(Request $request, $nomor_pendaftaran)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') !== $nomor_pendaftaran) {
            return redirect()->route('santri.check.form')->with('error', 'Akses tidak diizinkan.');
        }

        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor_pendaftaran)
            ->firstOrFail();

        return view('santri.dashboard.index', compact('santri'));
    }

    /**
     * Menampilkan halaman cek biaya administrasi
     */
    public function cekbiayaadministrasi($nomor)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') !== $nomor) {
            return redirect()->route('santri.check.form')->with('error', 'Akses tidak diizinkan.');
        }

        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor)
            ->firstOrFail();
        return view('santri.pembayaran.biayaadministrasi', compact('santri'));
    }

    /**
     * Menampilkan halaman cek daftar ulang
     */
    public function cekdaftarulang($nomor)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') !== $nomor) {
            return redirect()->route('santri.check.form')->with('error', 'Akses tidak diizinkan.');
        }

        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor)
            ->firstOrFail();
        return view('santri.pembayaran.daftarulang', compact('santri'));
    }

    /**
     * Menampilkan halaman pembayaran
     */
    public function pembayaran($nomor_pendaftaran)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') !== $nomor_pendaftaran) {
            return redirect()->route('santri.check.form')->with('error', 'Akses tidak diizinkan.');
        }

        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor_pendaftaran)
            ->firstOrFail();

        return view('santri.pembayaran.index', compact('santri'));
    }

    /**
     * Logout santri
     */
    public function logout()
    {
        session()->forget(['nomor_pendaftaran', 'nama_santri', 'is_santri_logged_in']);
        return redirect()->route('santri.check.form')->with('success', 'Berhasil logout.');
    }

    /**
     * Menampilkan halaman informasi ujian santri
     */
    public function ujian($nomor_pendaftaran)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') != $nomor_pendaftaran) {
            return redirect()->route('santri.dashboard.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]);
        }

        // Ambil data santri
        $santri = Santri::where('nomor_pendaftaran', $nomor_pendaftaran)->firstOrFail();

        return view('santri.ujian.index', compact('santri'));
    }

    /**
     * Menampilkan halaman data santri
     */
    public function data($nomor_pendaftaran)
    {
        // Cek apakah user sudah login sebagai santri
        if (!session()->has('is_santri_logged_in')) {
            return redirect()->route('santri.check.form');
        }

        // Cek apakah nomor pendaftaran sesuai dengan yang login
        if (session('nomor_pendaftaran') != $nomor_pendaftaran) {
            return redirect()->route('santri.dashboard.index', ['nomor_pendaftaran' => session('nomor_pendaftaran')]);
        }

        // Ambil data santri beserta relasi orangTua
        $santri = Santri::with('orangTua')->where('nomor_pendaftaran', $nomor_pendaftaran)->firstOrFail();

        return view('santri.data.index', compact('santri'));
    }
}
