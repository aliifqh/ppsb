<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SantriExport;
use App\Models\SantriMagicToken;
use Carbon\Carbon;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with(['pembayaran']);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $santri = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.santri.index', compact('santri'));
    }

    public function create()
    {
        return view('admin.santri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:santri',
            'nomor_pendaftaran' => 'required|string|unique:santri',
            'unit' => 'required|in:ppim,tks',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'asal_sekolah' => 'nullable|string',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'status_tes' => 'nullable|string',

            // Data orang tua
            'orang_tua.nama_ayah' => 'required|string',
            'orang_tua.pekerjaan_ayah' => 'required|string',
            'orang_tua.pendidikan_ayah' => 'required|string',
            'orang_tua.wa_ayah' => 'required|string',
            'orang_tua.nama_ibu' => 'required|string',
            'orang_tua.pekerjaan_ibu' => 'required|string',
            'orang_tua.pendidikan_ibu' => 'required|string',
            'orang_tua.wa_ibu' => 'required|string',
            'orang_tua.alamat' => 'required|string',
            'orang_tua.provinsi_id' => 'required|string',
            'orang_tua.kabupaten_id' => 'required|string',
            'orang_tua.kecamatan_id' => 'required|string',
            'orang_tua.desa_id' => 'required|string',
            'orang_tua.kode_pos' => 'required|string',

            // Dokumen (opsional dalam pembuatan awal)
            'dokumen.pasfoto' => 'nullable|file|image|max:3072',
            'dokumen.akta_lahir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'dokumen.kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'dokumen.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        // Simpan data santri
        $santri = Santri::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'nomor_pendaftaran' => $request->nomor_pendaftaran,
            'unit' => $request->unit,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'asal_sekolah' => $request->asal_sekolah,
            'anak_ke' => $request->anak_ke,
            'jumlah_saudara' => $request->jumlah_saudara,
            'status_tes' => $request->status_tes,
        ]);

        // Simpan data orang tua
        $orangTuaData = $request->orang_tua;
        $orangTuaData['santri_id'] = $santri->id;
        $orangTua = OrangTua::create($orangTuaData);

        // Simpan dokumen jika ada
        if ($request->hasFile('dokumen')) {
            $dokumenData = ['santri_id' => $santri->id];

            foreach ($request->file('dokumen') as $key => $file) {
                $path = $file->store('dokumen/' . $santri->id);
                $dokumenData[$key] = $path;
            }

            Dokumen::create($dokumenData);
        }

        return response()->json([
            'message' => 'Data santri berhasil ditambahkan',
            'data' => $santri->load(['orangTua', 'dokumen'])
        ], 201);
    }

    public function show(Santri $santri)
    {
        $santri->load(['orangTua', 'dokumen', 'pembayaran']);
        return view('admin.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        return view('admin.santri.edit', compact('santri'));
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:santri,nisn,' . $id,
            'nomor_pendaftaran' => 'required|string|unique:santri,nomor_pendaftaran,' . $id,
            'unit' => 'required|in:ppim,tks',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'asal_sekolah' => 'nullable|string',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'status_tes' => 'nullable|string',

            // Data orang tua
            'orang_tua.nama_ayah' => 'required|string',
            'orang_tua.pekerjaan_ayah' => 'required|string',
            'orang_tua.pendidikan_ayah' => 'required|string',
            'orang_tua.wa_ayah' => 'required|string',
            'orang_tua.nama_ibu' => 'required|string',
            'orang_tua.pekerjaan_ibu' => 'required|string',
            'orang_tua.pendidikan_ibu' => 'required|string',
            'orang_tua.wa_ibu' => 'required|string',
            'orang_tua.alamat' => 'required|string',
            'orang_tua.provinsi_id' => 'required|string',
            'orang_tua.kabupaten_id' => 'required|string',
            'orang_tua.kecamatan_id' => 'required|string',
            'orang_tua.desa_id' => 'required|string',
            'orang_tua.kode_pos' => 'required|string',

            // Dokumen (opsional dalam update)
            'dokumen.pasfoto' => 'nullable|file|image|max:3072',
            'dokumen.akta_lahir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'dokumen.kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'dokumen.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        // Update data santri
        $santri->update([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'nomor_pendaftaran' => $request->nomor_pendaftaran,
            'unit' => $request->unit,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'asal_sekolah' => $request->asal_sekolah,
            'anak_ke' => $request->anak_ke,
            'jumlah_saudara' => $request->jumlah_saudara,
            'status_tes' => $request->status_tes,
        ]);

        // Update data orang tua
        if ($santri->orangTua) {
            $santri->orangTua->update($request->orang_tua);
        } else {
            $orangTuaData = $request->orang_tua;
            $orangTuaData['santri_id'] = $santri->id;
            OrangTua::create($orangTuaData);
        }

        // Update dokumen jika ada
        if ($request->has('dokumen')) {
            $dokumenPaths = [];
            $existingDokumen = $santri->dokumen;

            foreach ($request->dokumen as $key => $file) {
                // Pastikan $file adalah objek file yang diupload, bukan string path
                if (is_a($file, 'Illuminate\\Http\\UploadedFile')) {
                    // Hapus file lama jika ada
                    if ($existingDokumen && $existingDokumen->{$key} && Storage::disk('public')->exists($existingDokumen->{$key})) {
                        Storage::disk('public')->delete($existingDokumen->{$key});
                    }

                    // Simpan file baru dan kumpulkan pathnya
                    $dokumenPaths[$key] = $file->store($key, 'public');
                }
            }

            // Lakukan update hanya jika ada file baru yang diupload
            if (!empty($dokumenPaths)) {
                Dokumen::updateOrCreate(
                    ['santri_id' => $santri->id],
                    $dokumenPaths
                );
            }
        }

        return response()->json([
            'message' => 'Data santri berhasil diperbarui',
            'data' => $santri->load(['orangTua', 'dokumen'])
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $santri = Santri::with(['pembayaran', 'dokumen'])->findOrFail($id);

        // Jika ada parameter 'force', bypass pengecekan bukti dan langsung soft delete
        if ($request->input('force') === 'true') {
            $santri->delete();
            return response()->json(['message' => 'Data santri berhasil dipindahkan ke data terhapus (paksa).']);
        }

        // Logika hapus biasa (dengan pengecekan)
        if ($santri->pembayaran) {
            $p = $santri->pembayaran;
            $hasBukti = $p->bukti_biaya_administrasi ||
                        $p->bukti_biaya_daftar_ulang ||
                        $p->bukti_biaya_administrasi_admin ||
                        $p->bukti_biaya_daftar_ulang_admin;

            if ($hasBukti) {
                return response()->json([
                    'message' => 'Gagal menghapus. Santri ini sudah melakukan pembayaran.'
                ], 422);
            }
        }

        $santri->delete();
        return response()->json(null, 204);
    }

    public function export()
    {
        return Excel::download(new SantriExport, 'data-santri.xlsx');
    }

    /**
     * Menampilkan daftar santri yang telah dihapus
     */
    public function trashed(Request $request)
    {
        $search = $request->search;

        $santri = Santri::onlyTrashed()
            ->with(['pembayaran'])
            ->when($search, function($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
            })
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.santri.trashed', compact('santri'));
    }

    /**
     * Mengembalikan santri yang telah dihapus
     */
    public function restore($id)
    {
        $santri = Santri::onlyTrashed()->findOrFail($id);
        $santri->restore();

        // Kembalikan juga data pembayaran jika ada
        if ($santri->pembayaran()->withTrashed()->exists()) {
            $santri->pembayaran()->withTrashed()->restore();
        }

        return redirect()->route('admin.santri.trashed')
            ->with('success', 'Data santri berhasil dikembalikan');
    }

    /**
     * Menghapus santri secara permanen
     */
    public function forceDelete($id)
    {
        $santri = Santri::onlyTrashed()->findOrFail($id);
        $santri->forceDelete();

        return response()->json(['success' => true, 'message' => 'Data santri berhasil dihapus secara permanen']);
    }

    public function getData()
    {
        $santri = Santri::with(['orangTua', 'dokumen', 'pembayaran', 'gelombang'])->get();
        return response()->json($santri);
    }

    public function getTrashedData(Request $request)
    {
        $search = $request->search;

        $santri = Santri::onlyTrashed()
            ->with(['pembayaran'])
            ->when($search, function($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
            })
            ->latest('deleted_at')
            ->paginate(10);

        return response()->json($santri);
    }

    public function getApiData(Request $request)
    {
        $query = Santri::with(['orangTua', 'dokumen', 'pembayaran']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Sorting
        if ($request->has('sortKey')) {
            $sortKey = $request->sortKey;
            $sortAsc = $request->get('sortAsc', 'true') === 'true';
            $query->orderBy($sortKey, $sortAsc ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Paginasi
        $perPage = $request->get('perPage', 10);
        if ($perPage == -1) {
            $data = $query->get();
            // Buat respons manual yang strukturnya mirip paginator
            return response()->json([
                'data' => $data,
                'total' => $data->count(),
                'per_page' => $data->count(),
                'current_page' => 1,
                'last_page' => 1,
                'from' => 1,
                'to' => $data->count(),
            ]);
        }

        $santri = $query->paginate($perPage);

        return response()->json($santri);
    }

    public function getTrashedApiData(Request $request)
    {
        $query = Santri::onlyTrashed()->with(['gelombang', 'pembayaran']);

        // Server-side search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Server-side sorting
        $sortKey = $request->input('sortKey', 'deleted_at');
        $sortAsc = $request->input('sortAsc', 'false') === 'true';
        $direction = $sortAsc ? 'asc' : 'desc';

        $query->orderBy($sortKey, $direction);

        // Server-side pagination
        $perPage = $request->input('perPage', 10);
        $santri = $query->paginate($perPage);

        return response()->json($santri);
    }

    public function updateStatusTes(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'status_tes' => ['required', 'string', 'in:Lulus,Tidak Lulus,Menunggu Tes'],
        ]);

        $santri->status_tes = $validated['status_tes'];
        $santri->save();

        return response()->json(['message' => 'Status tes berhasil diperbarui.']);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:santri,id',
        ]);

        $ids = $validated['ids'];

        // Cek apakah ada santri yang dipilih yang sudah punya data pembayaran dengan bukti
        $countWithPembayaran = Santri::whereIn('id', $ids)
            ->whereHas('pembayaran', function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('bukti_biaya_administrasi')
                        ->orWhereNotNull('bukti_biaya_daftar_ulang')
                        ->orWhereNotNull('bukti_biaya_administrasi_admin')
                        ->orWhereNotNull('bukti_biaya_daftar_ulang_admin');
                });
            })
            ->count();


        if ($countWithPembayaran > 0) {
            return response()->json([
                'message' => "Gagal menghapus. Terdapat {$countWithPembayaran} santri yang sudah melakukan pembayaran dan tidak bisa dihapus."
            ], 422);
        }

        Santri::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Santri yang dipilih berhasil dihapus.']);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:santri,id',
            'status_tes' => 'required|string|in:Lulus,Tidak Lulus,Menunggu Tes',
        ]);

        Santri::whereIn('id', $request->ids)->update(['status_tes' => $request->status_tes]);

        return response()->json(['message' => 'Status tes berhasil diperbarui untuk santri yang dipilih.']);
    }

    public function bulkRestore(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:santri,id',
        ]);

        $ids = $validated['ids'];
        Santri::whereIn('id', $ids)->onlyTrashed()->restore();

        return response()->json(['message' => 'Santri yang dipilih berhasil dipulihkan.']);
    }

    public function bulkForceDelete(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:santri,id',
        ]);

        $ids = $validated['ids'];
        $santriToDelete = Santri::whereIn('id', $ids)->onlyTrashed();

        // Optional: Hapus file terkait jika ada sebelum force delete
        foreach ($santriToDelete->get() as $santri) {
            if ($santri->dokumen) {
                foreach (['pasfoto', 'akta_lahir', 'kartu_keluarga', 'ijazah'] as $fileType) {
                    if ($santri->dokumen->{$fileType}) {
                        Storage::disk('public')->delete($santri->dokumen->{$fileType});
                    }
                }
            }
        }

        $santriToDelete->forceDelete();

        return response()->json(['message' => 'Santri yang dipilih berhasil dihapus permanen.']);
    }

    public function changeGelombang(Request $request, Santri $santri)
    {
        $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
        ]);

        $santri->update([
            'gelombang_id' => $request->gelombang_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gelombang santri berhasil diperbarui!',
        ]);
    }

    public function generateMagicLink($id)
    {
        $santri = Santri::findOrFail($id);
        // Cari token aktif yang belum expired dan belum dipakai
        $token = $santri->magicTokens()->whereNull('used_at')->where('expired_at', '>', Carbon::now())->first();
        if (!$token) {
            $token = SantriMagicToken::generateFor($santri->id, 60*24); // expired 24 jam
        }
        $link = route('santri.magic.login', ['token' => $token->token]);
        return response()->json(['link' => $link]);
    }
}
