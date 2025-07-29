<?php

namespace App\Http\Controllers\Santri;

use App\Models\Santri;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Helpers\FileNameHelper;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class SantriPembayaranController extends Controller
{
    public function __construct()
    {
        // Perbaiki path file yang sudah ada
        $pembayaran = Pembayaran::where('bukti_biaya_administrasi', 'like', 'public/bukti-administrasi/%')->get();
        foreach ($pembayaran as $p) {
            $newPath = str_replace('public/bukti-administrasi/', 'bukti-administrasi/', $p->bukti_biaya_administrasi);
            $p->update(['bukti_biaya_administrasi' => $newPath]);
        }

        $pembayaran = Pembayaran::where('bukti_biaya_daftar_ulang', 'like', 'public/bukti-daftar-ulang/%')->get();
        foreach ($pembayaran as $p) {
            $newPath = str_replace('public/bukti-daftar-ulang/', 'bukti-daftar-ulang/', $p->bukti_biaya_daftar_ulang);
            $p->update(['bukti_biaya_daftar_ulang' => $newPath]);
        }
    }

    /**
     * Menampilkan halaman pembayaran biaya administrasi (untuk santri yang login)
     */
    public function biayaadministrasi($nomor_pendaftaran)
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
        return view('santri.pembayaran.biayaadministrasi', compact('santri'));
    }

    /**
     * Menampilkan halaman pembayaran biaya administrasi (untuk akses publik)
     */
    public function biayaadministrasiPublic($nomor_pendaftaran)
    {
        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor_pendaftaran)
            ->firstOrFail();
        return view('santri.pembayaran.biayaadministrasi', compact('santri'));
    }

    /**
     * Menampilkan halaman pembayaran daftar ulang (untuk santri yang login)
     */
    public function daftarulang($nomor_pendaftaran)
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
        return view('santri.pembayaran.daftarulang', compact('santri'));
    }

    /**
     * Menampilkan halaman pembayaran daftar ulang (untuk akses publik)
     */
    public function daftarulangPublic($nomor_pendaftaran)
    {
        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran'])
            ->where('nomor_pendaftaran', $nomor_pendaftaran)
            ->firstOrFail();
        return view('santri.pembayaran.daftarulang', compact('santri'));
    }

    /**
     * Upload bukti pembayaran administrasi
     */
    public function uploadBuktiAdministrasi(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nominal' => 'required|string',
            'tanggal_transfer' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tanggal_transfer.required' => 'Tanggal transfer harus diisi',
            'tanggal_transfer.date' => 'Format tanggal tidak valid',
            'nominal.required' => 'Nominal transfer harus diisi',
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diunggah',
            'bukti_pembayaran.mimes' => 'Format file harus jpeg, png, atau jpg',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            // Proses nominal (hapus format currency)
            $nominal = str_replace(['Rp', '.', ' ', 'IDR', ',00'], '', $request->nominal);
            $nominal = (float) str_replace(',', '.', $nominal);

            // Proses tanggal
            $tanggal = Carbon::parse($request->tanggal_transfer)->format('Y-m-d');

            // Upload file
            $path = $request->file('bukti_pembayaran')->store('bukti-administrasi', 'public');

            // Cek apakah sudah ada data pembayaran
            $pembayaran = Pembayaran::where('santri_id', $request->santri_id)->first();

            if (!$pembayaran) {
                // Jika belum ada, buat data baru
                $pembayaran = Pembayaran::create([
                    'santri_id' => $request->santri_id,
                    'biaya_administrasi' => $nominal,
                    'status_administrasi' => 'sudah_bayar',
                    'bukti_biaya_administrasi' => $path,
                    'tanggal_administrasi' => $tanggal,
                    'biaya_daftar_ulang' => 0,
                    'status_daftar_ulang' => 'belum_bayar',
                    'nominal_transfer_administrasi' => $nominal,
                    'keterangan_administrasi' => $request->keterangan,
                ]);
            } else {
                // Jika sudah ada, update data
                // Hapus file lama jika ada
                if ($pembayaran->bukti_biaya_administrasi) {
                    Storage::delete($pembayaran->bukti_biaya_administrasi);
                }

                $pembayaran->update([
                    'biaya_administrasi' => $nominal,
                    'status_administrasi' => 'sudah_bayar',
                    'bukti_biaya_administrasi' => $path,
                    'tanggal_administrasi' => $tanggal,
                    'nominal_transfer_administrasi' => $nominal,
                    'keterangan_administrasi' => $request->keterangan,
                ]);
            }

            // Get santri data
            $santri = Santri::find($request->santri_id);

            // Set session for auto login
            session([
                'is_santri_logged_in' => true,
                'santri_id' => $santri->id,
                'nomor_pendaftaran' => $santri->nomor_pendaftaran,
                'nama_lengkap' => $santri->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran administrasi berhasil diunggah dan sedang menunggu verifikasi admin.',
                'redirect_url' => route('santri.pembayaran.index', ['nomor_pendaftaran' => $santri->nomor_pendaftaran])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saat upload bukti pembayaran administrasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload bukti pembayaran daftar ulang
     */
    public function uploadBuktiDaftarUlang(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nominal' => 'required|string',
            'tanggal_transfer' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tanggal_transfer.required' => 'Tanggal transfer harus diisi',
            'tanggal_transfer.date' => 'Format tanggal tidak valid',
            'nominal.required' => 'Nominal transfer harus diisi',
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diunggah',
            'bukti_pembayaran.mimes' => 'Format file harus jpeg, png, atau jpg',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            // Proses nominal (hapus format currency)
            $nominal = str_replace(['Rp', '.', ' ', 'IDR', ',00'], '', $request->nominal);
            $nominal = (float) str_replace(',', '.', $nominal);

            // Proses tanggal
            $tanggal = Carbon::parse($request->tanggal_transfer)->format('Y-m-d');

            // Upload file
            $path = $request->file('bukti_pembayaran')->store('bukti-daftar-ulang', 'public');

            // Cek apakah sudah ada data pembayaran
            $pembayaran = Pembayaran::where('santri_id', $request->santri_id)->first();

            if (!$pembayaran) {
                // Jika belum ada, buat data baru
                $pembayaran = Pembayaran::create([
                    'santri_id' => $request->santri_id,
                    'biaya_daftar_ulang' => $nominal,
                    'status_daftar_ulang' => 'sudah_bayar',
                    'bukti_biaya_daftar_ulang' => $path,
                    'tanggal_daftar_ulang' => $tanggal,
                    'biaya_pendaftaran' => 0,
                    'status_pendaftaran' => 'belum_bayar',
                    'nominal_transfer_daftar_ulang' => $nominal,
                    'keterangan_daftar_ulang' => $request->keterangan,
                ]);
            } else {
                // Jika sudah ada, update data
                // Hapus file lama jika ada
                if ($pembayaran->bukti_biaya_daftar_ulang) {
                    Storage::delete($pembayaran->bukti_biaya_daftar_ulang);
                }

                $pembayaran->update([
                    'biaya_daftar_ulang' => $nominal,
                    'status_daftar_ulang' => 'sudah_bayar',
                    'bukti_biaya_daftar_ulang' => $path,
                    'tanggal_daftar_ulang' => $tanggal,
                    'nominal_transfer_daftar_ulang' => $nominal,
                    'keterangan_daftar_ulang' => $request->keterangan,
                ]);
            }

            // Get santri data
            $santri = Santri::find($request->santri_id);

            // Set session for auto login
            session([
                'is_santri_logged_in' => true,
                'santri_id' => $santri->id,
                'nomor_pendaftaran' => $santri->nomor_pendaftaran,
                'nama_lengkap' => $santri->nama
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran daftar ulang berhasil diunggah dan sedang menunggu verifikasi admin.',
                'redirect_url' => route('santri.pembayaran.index', ['nomor_pendaftaran' => $santri->nomor_pendaftaran])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saat upload bukti pembayaran daftar ulang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan status pembayaran santri
     */
    public function cekPembayaran($nomor_pendaftaran)
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
     * Menampilkan halaman pembayaran
     */
    public function index($nomor_pendaftaran)
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
     * Redirect dari biaya pendaftaran ke biaya administrasi (untuk backward compatibility)
     */
    public function biayaPendaftaran($nomor_pendaftaran)
    {
        return redirect()->route('santri.pembayaran.biayaadministrasi', ['nomor_pendaftaran' => $nomor_pendaftaran]);
    }

    /**
     * Redirect dari biaya pendaftaran public ke biaya administrasi public (untuk backward compatibility)
     */
    public function biayapendaftaranPublic($nomor_pendaftaran)
    {
        return redirect()->route('public.biayaadministrasi', ['nomor_pendaftaran' => $nomor_pendaftaran]);
    }
}
