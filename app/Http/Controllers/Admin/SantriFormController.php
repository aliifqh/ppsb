<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Dokumen;
use App\Models\Pembayaran;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\DateHelper;
use App\Helpers\FileNameHelper;

class SantriFormController extends Controller
{
    public function create()
    {
        $gelombang = Gelombang::where('status', 'aktif')->first();
        return view('admin.santri.form', compact('gelombang'));
    }

    public function store(Request $request)
    {
        // Validasi semua data
        $request->validate(Santri::rules(), Santri::messages());

        // Generate nomor pendaftaran
        $tahun = date('Y');
        $count = Santri::whereYear('created_at', $tahun)->count() + 1;
        $randomString = strtoupper(Str::random(4));
        $nomorPendaftaran = "PPSB-PPIN-{$tahun}-" . str_pad($count, 4, '0', STR_PAD_LEFT) . "-{$randomString}";

        // Ambil gelombang aktif
        $gelombang = Gelombang::where('status', 'aktif')->first();

        // Upload file
        $pasfoto = $request->file('pasfoto');
        $pasfotoName = FileNameHelper::generate($request->unit, $request->nama, 'pasfoto', $pasfoto->getClientOriginalExtension());
        $pasfotoPath = $pasfoto->storeAs('pasfoto', $pasfotoName, 'public');

        $aktaLahir = $request->file('akta_lahir');
        $aktaLahirName = FileNameHelper::generate($request->unit, $request->nama, 'akta_lahir', $aktaLahir->getClientOriginalExtension());
        $aktaLahirPath = $aktaLahir->storeAs('akta_lahir', $aktaLahirName, 'public');

        $kartuKeluarga = $request->file('kartu_keluarga');
        $kartuKeluargaName = FileNameHelper::generate($request->unit, $request->nama, 'kartu_keluarga', $kartuKeluarga->getClientOriginalExtension());
        $kartuKeluargaPath = $kartuKeluarga->storeAs('kartu_keluarga', $kartuKeluargaName, 'public');

        $ijazah = $request->file('ijazah');
        $ijazahName = FileNameHelper::generate($request->unit, $request->nama, 'ijazah', $ijazah->getClientOriginalExtension());
        $ijazahPath = $ijazah->storeAs('ijazah', $ijazahName, 'public');

        // Upload dokumen pendukung jika ada
        $dokumenPendukungPaths = [];
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $index => $file) {
                $fileName = FileNameHelper::generate($request->unit, $request->nama, 'dokumen_pendukung_'.$index, $file->getClientOriginalExtension());
                $path = $file->storeAs('dokumen_pendukung', $fileName, 'public');
                $dokumenPendukungPaths[] = $path;
            }
        }

        // Buat data santri
        $santri = Santri::create([
            'nomor_pendaftaran' => $nomorPendaftaran,
            'unit' => $request->unit,
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'asal_sekolah' => $request->asal_sekolah,
            'anak_ke' => $request->anak_ke,
            'jumlah_saudara' => $request->jumlah_saudara,
            'pasfoto' => $pasfotoPath,
            'akta_lahir' => $aktaLahirPath,
            'kartu_keluarga' => $kartuKeluargaPath,
            'ijazah' => $ijazahPath,
            'dokumen_pendukung' => $dokumenPendukungPaths,
            'status_tes' => 'Menunggu Tes',
            'gelombang_id' => $gelombang ? $gelombang->id : null,
        ]);

        // Buat data orang tua
        OrangTua::create([
            'santri_id' => $santri->id,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pekerjaan_ayah_lainnya' => $request->pekerjaan_ayah_lainnya,
            'pekerjaan_ibu_lainnya' => $request->pekerjaan_ibu_lainnya,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'wa_ayah' => $request->wa_ayah,
            'wa_ibu' => $request->wa_ibu,
            'alamat' => $request->alamat,
            'provinsi_id' => $request->provinsi,
            'kabupaten_id' => $request->kabupaten,
            'kecamatan_id' => $request->kecamatan,
            'desa_id' => $request->desa,
            'kode_pos' => $request->kode_pos,
        ]);

        // Buat data pembayaran
        Pembayaran::create([
            'santri_id' => $santri->id,
            'biaya_administrasi' => $gelombang ? $gelombang->biaya_administrasi : 0,
            'biaya_daftar_ulang' => $gelombang ? $gelombang->biaya_daftar_ulang : 0,
            'status_administrasi' => 'belum_bayar',
            'status_daftar_ulang' => 'belum_bayar',
        ]);

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }
}
