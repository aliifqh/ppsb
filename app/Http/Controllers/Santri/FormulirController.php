<?php

namespace App\Http\Controllers\Santri;

use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Dokumen;
use App\Models\Pembayaran;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Helpers\DateHelper;
use App\Helpers\FileNameHelper;
use App\Helpers\LocationHelper;
use DB;
use App\Http\Controllers\Controller;

class FormulirController extends Controller
{
    /**
     * Upload file ke storage
     */
    private function uploadFile($file, $folder)
    {
        if (!$file) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = FileNameHelper::generate(
            request()->unit,
            request()->nama,
            $folder,
            $extension
        );
        $path = $file->storeAs($folder, $fileName, 'public');

        return $fileName;
    }

    /**
     * Menampilkan data santri
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

        // Ambil data santri beserta relasi orangTua
        $santri = Santri::with('orangTua')->where('nomor_pendaftaran', $nomor_pendaftaran)->firstOrFail();

        return view('santri.data.index', compact('santri'));
    }

    /**
     * Menampilkan formulir pendaftaran baru
     */
    public function create()
    {
        $gelombang = Gelombang::where('status', 1)
            ->get()
            ->first(function($g) {
                return $g->isActive();
            });
        return view('pages.formulir', compact('gelombang'));
    }

    /**
     * Memproses pengiriman formulir
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validator = Validator::make($request->all(), [
                'unit' => 'required|in:ppim,tks',
                'nama' => 'required|string|max:255',
                'nisn' => 'required|string|size:10',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|string',
                'asal_sekolah' => 'required|string|max:255',
                'anak_ke' => 'required|integer|min:1',
                'jumlah_saudara' => 'required|integer|min:1',
                'nama_ayah' => 'required|string|max:255',
                'nama_ibu' => 'required|string|max:255',
                'pekerjaan_ayah' => 'required|string',
                'pekerjaan_ibu' => 'required|string',
                'pendidikan_ayah' => 'required|string',
                'pendidikan_ibu' => 'required|string',
                'wa_ayah' => 'required|string',
                'wa_ibu' => 'required|string',
                'alamat' => 'required|string',
                'provinsi_id' => 'required|string',
                'kabupaten_id' => 'required|string',
                'kecamatan_id' => 'required|string',
                'desa_id' => 'required|string',
                'kode_pos' => 'required|string|size:5',
                'pasfoto' => 'required|file|mimes:jpeg,png|max:3072',
                'akta_lahir' => 'required|file|mimes:jpeg,png,pdf|max:3072',
                'kartu_keluarga' => 'required|file|mimes:jpeg,png,pdf|max:3072',
                'ijazah' => 'required|file|mimes:jpeg,png,pdf|max:3072',
                'dokumen_pendukung.*' => 'nullable|file|mimes:jpeg,png,pdf|max:3072'
            ], [
                'unit.required' => 'Unit harus diisi.',
                'unit.in' => 'Unit harus PPIM atau TKS.',
                'nama.required' => 'Nama harus diisi.',
                'nama.max' => 'Nama maksimal 255 karakter.',
                'nisn.required' => 'NISN harus diisi.',
                'nisn.size' => 'NISN harus 10 karakter.',
                'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
                'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                'tempat_lahir.required' => 'Tempat lahir harus diisi.',
                'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',
                'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
                'asal_sekolah.required' => 'Asal sekolah harus diisi.',
                'asal_sekolah.max' => 'Asal sekolah maksimal 255 karakter.',
                'anak_ke.required' => 'Anak ke harus diisi.',
                'anak_ke.integer' => 'Anak ke harus berupa angka.',
                'anak_ke.min' => 'Anak ke minimal 1.',
                'jumlah_saudara.required' => 'Jumlah saudara harus diisi.',
                'jumlah_saudara.integer' => 'Jumlah saudara harus berupa angka.',
                'jumlah_saudara.min' => 'Jumlah saudara minimal 1.',
                'nama_ayah.required' => 'Nama ayah harus diisi.',
                'nama_ayah.max' => 'Nama ayah maksimal 255 karakter.',
                'nama_ibu.required' => 'Nama ibu harus diisi.',
                'nama_ibu.max' => 'Nama ibu maksimal 255 karakter.',
                'pekerjaan_ayah.required' => 'Pekerjaan ayah harus diisi.',
                'pekerjaan_ibu.required' => 'Pekerjaan ibu harus diisi.',
                'pendidikan_ayah.required' => 'Pendidikan ayah harus diisi.',
                'pendidikan_ibu.required' => 'Pendidikan ibu harus diisi.',
                'wa_ayah.required' => 'Nomor WA ayah harus diisi.',
                'wa_ibu.required' => 'Nomor WA ibu harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
                'provinsi_id.required' => 'Provinsi harus diisi.',
                'kabupaten_id.required' => 'Kabupaten harus diisi.',
                'kecamatan_id.required' => 'Kecamatan harus diisi.',
                'desa_id.required' => 'Desa harus diisi.',
                'kode_pos.required' => 'Kode pos harus diisi.',
                'kode_pos.size' => 'Kode pos harus 5 karakter.',
                'pasfoto.required' => 'Pas foto harus diunggah.',
                'pasfoto.mimes' => 'Pas foto harus berformat JPEG atau PNG.',
                'pasfoto.max' => 'Pas foto maksimal 3MB.',
                'akta_lahir.required' => 'Akta lahir harus diunggah.',
                'akta_lahir.mimes' => 'Akta lahir harus berformat JPEG, PNG, atau PDF.',
                'akta_lahir.max' => 'Akta lahir maksimal 3MB.',
                'kartu_keluarga.required' => 'Kartu keluarga harus diunggah.',
                'kartu_keluarga.mimes' => 'Kartu keluarga harus berformat JPEG, PNG, atau PDF.',
                'kartu_keluarga.max' => 'Kartu keluarga maksimal 3MB.',
                'ijazah.required' => 'Ijazah harus diunggah.',
                'ijazah.mimes' => 'Ijazah harus berformat JPEG, PNG, atau PDF.',
                'ijazah.max' => 'Ijazah maksimal 3MB.',
                'dokumen_pendukung.*.mimes' => 'Dokumen pendukung harus berformat JPEG, PNG, atau PDF.',
                'dokumen_pendukung.*.max' => 'Dokumen pendukung maksimal 3MB.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate nomor pendaftaran
            $tahun = date('Y');
            $count = Santri::whereYear('created_at', $tahun)->count() + 1;
            $randomString = strtoupper(Str::random(4));
            $nomorPendaftaran = "PPSB-PPIN-{$tahun}-" . str_pad($count, 4, '0', STR_PAD_LEFT) . "-{$randomString}";

            // Convert tanggal lahir format
            $tanggalLahir = DateHelper::convertToDate($request->tanggal_lahir);

            // Create santri record
            $gelombang = Gelombang::where('status', 1)
                ->get()
                ->first(function($g) {
                    return $g->isActive();
                });

            if (!$gelombang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada gelombang pendaftaran yang aktif saat ini'
                ], 422);
            }

            $santri = Santri::create([
                'nomor_pendaftaran' => $nomorPendaftaran,
                'unit' => $request->unit,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $tanggalLahir,
                'asal_sekolah' => $request->asal_sekolah,
                'anak_ke' => $request->anak_ke,
                'jumlah_saudara' => $request->jumlah_saudara,
                'status_tes' => 'belum',
                'gelombang_id' => $gelombang->id,
            ]);

            // Create orang tua record
            $orangTua = OrangTua::create([
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
                'provinsi_id' => LocationHelper::getProvinceName($request->provinsi_id),
                'kabupaten_id' => LocationHelper::getRegencyName($request->kabupaten_id),
                'kecamatan_id' => LocationHelper::getDistrictName($request->kecamatan_id),
                'desa_id' => LocationHelper::getVillageName($request->desa_id),
                'kode_pos' => $request->kode_pos
            ]);

            // Handle file uploads
            $files = [];
            foreach (['pasfoto', 'akta_lahir', 'kartu_keluarga', 'ijazah'] as $type) {
                if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $extension = $file->getClientOriginalExtension();
                    $filename = FileNameHelper::generate($request->unit, $request->nama, $type, $extension);
                    $path = $file->storeAs($type, $filename, 'public');
                    $files[$type] = $filename;
                }
            }

            // Handle optional dokumen pendukung
            $dokumenPendukung = [];
            if ($request->hasFile('dokumen_pendukung')) {
                foreach ($request->file('dokumen_pendukung') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = FileNameHelper::generate($request->unit, $request->nama, 'dokumen_pendukung', $extension);
                    $path = $file->storeAs('dokumen_pendukung', $filename, 'public');
                    $dokumenPendukung[] = $filename;
                }
            }

            // Create dokumen record
            $dokumen = Dokumen::create([
                'santri_id' => $santri->id,
                'pasfoto' => $files['pasfoto'],
                'akta_lahir' => $files['akta_lahir'],
                'kartu_keluarga' => $files['kartu_keluarga'],
                'ijazah' => $files['ijazah'],
                'dokumen_pendukung' => $dokumenPendukung
            ]);

            // Create pembayaran record
            $pembayaran = Pembayaran::create([
                'santri_id' => $santri->id,
                'biaya_administrasi' => $gelombang ? $gelombang->biaya_administrasi : 0,
                'biaya_daftar_ulang' => $gelombang ? $gelombang->biaya_daftar_ulang : 0,
                'status_administrasi' => 'belum_bayar',
                'status_daftar_ulang' => 'belum_bayar',
                'bukti_biaya_administrasi' => null,
                'bukti_biaya_daftar_ulang' => null,
                'tanggal_administrasi' => null,
                'tanggal_daftar_ulang' => null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil disimpan',
                'data' => $santri,
                'redirect_url' => route('public.biayaadministrasi', ['nomor_pendaftaran' => $nomorPendaftaran])
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            // Log detail error ke file log Laravel
            \Log::error('Error di FormulirController@store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Tampilkan pesan error yang jelas ke user
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi atau hubungi admin jika masalah berlanjut.'
            ], 500);
        }
    }
}
