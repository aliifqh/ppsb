<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Pembayaran;
use App\Models\Gelombang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function pengaturan()
    {
        return view('admin.pengaturan.index');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function getData()
    {
        // Total Santri aktif
        $totalSantri = Santri::count();
        // Total Santri terhapus (soft delete)
        $totalSantriTrashed = Santri::onlyTrashed()->count();

        // Pembayaran
        $totalPembayaran = Pembayaran::count();
        $totalPembayaranDiverifikasi = Pembayaran::where('status_administrasi', 'diverifikasi')
            ->orWhere('status_daftar_ulang', 'diverifikasi')->count();
        $totalPembayaranBelumVerif = Pembayaran::where(function($q){
            $q->where('status_administrasi', 'sudah_bayar')
              ->orWhere('status_daftar_ulang', 'sudah_bayar');
        })->count();
        $totalPembayaranBelumBayar = Pembayaran::where(function($q){
            $q->where('status_administrasi', 'belum_bayar')
              ->orWhere('status_daftar_ulang', 'belum_bayar');
        })->count();
        $totalUploadBukti = Pembayaran::whereNotNull('bukti_biaya_administrasi')
            ->orWhereNotNull('bukti_biaya_daftar_ulang')
            ->orWhereNotNull('bukti_biaya_administrasi_admin')
            ->orWhereNotNull('bukti_biaya_daftar_ulang_admin')->count();
        $totalBelumUploadBukti = $totalPembayaran - $totalUploadBukti;

        // Status Tes Santri
        $statusTes = [
            'Lulus' => Santri::where('status_tes', 'Lulus')->count(),
            'Tidak Lulus' => Santri::where('status_tes', 'Tidak Lulus')->count(),
            'Menunggu Tes' => Santri::where('status_tes', 'Menunggu Tes')->count(),
        ];

        // Gelombang aktif
        $gelombangAktif = Gelombang::where('status', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderByDesc('tanggal_mulai')
            ->first();
        $gelombangAktifData = $gelombangAktif ? [
            'id' => $gelombangAktif->id,
            'nama' => $gelombangAktif->nama,
            'tanggal_mulai' => $gelombangAktif->tanggal_mulai->format('d-m-Y'),
            'tanggal_selesai' => $gelombangAktif->tanggal_selesai->format('d-m-Y'),
            'biaya_administrasi' => $gelombangAktif->biaya_administrasi,
            'biaya_daftar_ulang' => $gelombangAktif->biaya_daftar_ulang,
            'status' => $gelombangAktif->getStatusText(),
        ] : null;

        return response()->json([
            'totalSantri' => $totalSantri,
            'totalSantriTrashed' => $totalSantriTrashed,
            'totalPembayaran' => $totalPembayaran,
            'totalPembayaranDiverifikasi' => $totalPembayaranDiverifikasi,
            'totalPembayaranBelumVerif' => $totalPembayaranBelumVerif,
            'totalPembayaranBelumBayar' => $totalPembayaranBelumBayar,
            'totalUploadBukti' => $totalUploadBukti,
            'totalBelumUploadBukti' => $totalBelumUploadBukti,
            'statusTes' => $statusTes,
            'gelombangAktif' => $gelombangAktifData,
        ]);
    }
}
