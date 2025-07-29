<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['santri.gelombang']);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('santri', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan tanggal pembayaran terbaru (administrasi/daftar ulang), jika null urutkan created_at
        $query->orderByRaw('GREATEST(COALESCE(tanggal_administrasi, "0000-00-00 00:00:00"), COALESCE(tanggal_daftar_ulang, "0000-00-00 00:00:00"), created_at) DESC');

        $pembayaran = $query->paginate(10);
        $totalPembayaran = $pembayaran->total();
        $totalSukses = $query->where(function($q) {
            $q->where('status_administrasi', 'diverifikasi')
              ->orWhere('status_daftar_ulang', 'diverifikasi');
        })->sum(\DB::raw('biaya_administrasi + biaya_daftar_ulang'));
        $totalPending = $query->where(function($q) {
            $q->where('status_administrasi', 'sudah_bayar')
              ->orWhere('status_daftar_ulang', 'sudah_bayar');
        })->sum(\DB::raw('biaya_administrasi + biaya_daftar_ulang'));

        return view('admin.pembayaran.index', compact('pembayaran', 'totalPembayaran', 'totalSukses', 'totalPending'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('santri.gelombang', 'santri.orangTua');
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pembayaran->load('santri');
        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_administrasi' => 'required|in:belum_bayar,sudah_bayar',
            'tanggal_administrasi' => 'nullable|date',
            'biaya_administrasi' => 'nullable|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string'
        ]);

        $data = $request->only([
            'status_administrasi',
            'tanggal_administrasi',
            'biaya_administrasi',
            'catatan'
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama jika ada
            if ($pembayaran->bukti_pembayaran) {
                Storage::delete($pembayaran->bukti_pembayaran);
            }

            // Upload file baru
            $path = $request->file('bukti_pembayaran')->store('public/bukti-pembayaran');
            $data['bukti_pembayaran'] = str_replace('public/', '', $path);
        }

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran.show', $pembayaran->id)
            ->with('success', 'Data pembayaran berhasil diperbarui');
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $jenis = $request->input('jenis');

        if ($jenis === 'administrasi') {
            $pembayaran->update([
                'status_administrasi' => 'diverifikasi',
                'tanggal_administrasi' => now()
            ]);
        } elseif ($jenis === 'daftar_ulang') {
            $pembayaran->update([
                'status_daftar_ulang' => 'diverifikasi',
                'tanggal_daftar_ulang' => now()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil diverifikasi.']);
    }

    public function batalkanVerifikasi(Request $request, Pembayaran $pembayaran)
    {
        $jenis = $request->input('jenis');

        if ($jenis === 'administrasi') {
            $pembayaran->update([
                'status_administrasi' => 'sudah_bayar',
                'tanggal_administrasi' => null
            ]);
        } elseif ($jenis === 'daftar_ulang') {
            $pembayaran->update([
                'status_daftar_ulang' => 'sudah_bayar',
                'tanggal_daftar_ulang' => null
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Verifikasi pembayaran berhasil dibatalkan.']);
    }

    public function export()
    {
        $filename = 'pembayaran_' . date('Y-m-d_H-i-s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PembayaranExport, $filename);
    }

    public function uploadBukti(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jenis' => 'required|in:administrasi,daftar_ulang',
            'langsung_verifikasi' => 'nullable|string'
        ]);

        $jenis = $request->jenis;
        $jenis_bukti_field_admin = 'bukti_biaya_' . $jenis . '_admin';

        if ($pembayaran->$jenis_bukti_field_admin) {
            Storage::disk('public')->delete($pembayaran->$jenis_bukti_field_admin);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        $updateData = [
            $jenis_bukti_field_admin => $path,
            'status_' . $jenis => 'sudah_bayar'
        ];

        $pembayaran->update($updateData);

        // Jika ada flag untuk langsung verifikasi
        if ($request->input('langsung_verifikasi') === 'true') {
            if ($jenis === 'administrasi') {
                $pembayaran->update([
                    'status_administrasi' => 'diverifikasi',
                    'tanggal_administrasi' => now()
                ]);
            } elseif ($jenis === 'daftar_ulang') {
                $pembayaran->update([
                    'status_daftar_ulang' => 'diverifikasi',
                    'tanggal_daftar_ulang' => now()
                ]);
            }
        }

        return response()->json([
            'message' => 'Bukti berhasil diupload!',
            'path' => $path
        ]);
    }

    public function getApiData(Request $request)
    {
        // Start with a clean query, select specific columns from pembayaran table
        $query = Pembayaran::query()->has('santri')->select('pembayaran.*')->with(['santri.gelombang']);

        // Server-side search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('santri', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('gelombang', function($g) use ($search) {
                      $g->where('nama', 'like', "%{$search}%");
                  });
            })->orWhere('status_administrasi', 'like', "%{$search}%")
              ->orWhere('status_daftar_ulang', 'like', "%{$search}%");
        }

        // Server-side sorting
        $sortKey = $request->input('sortKey', 'created_at');
        $sortAsc = $request->input('sortAsc', 'false') === 'true';
        $direction = $sortAsc ? 'asc' : 'desc';

        // Mapping sort keys from frontend to database columns
        $sortableColumns = [
            'santri' => 'santri.nama',
            'gelombang' => 'gelombangs.nama', // Use the actual table name from the join
            'status_administrasi' => 'pembayaran.status_administrasi',
            'status_daftar_ulang' => 'pembayaran.status_daftar_ulang',
        ];

        if (array_key_exists($sortKey, $sortableColumns)) {
             if ($sortKey === 'santri') {
                $query->leftJoin('santri', 'pembayaran.santri_id', '=', 'santri.id')
                      ->orderBy('santri.nama', $direction);
            } elseif ($sortKey === 'gelombang') {
                $query->leftJoin('santri', 'pembayaran.santri_id', '=', 'santri.id')
                      ->leftJoin('gelombangs', 'santri.gelombang_id', '=', 'gelombangs.id')
                      ->orderBy('gelombangs.nama', $direction);
            } else {
                 $query->orderBy($sortableColumns[$sortKey], $direction);
            }
        } else {
            // Default sort by pembayaran's created_at
            $query->orderBy('pembayaran.created_at', 'desc');
        }


        // Server-side pagination
        $perPage = $request->input('perPage', 10);

        if ($perPage == -1) {
            $data = $query->get();
            // Wrap in a 'data' key to match the export function's expectation
            return response()->json(['data' => $data]);
        }

        $pembayaran = $query->paginate($perPage);

        return response()->json($pembayaran);
    }

    public function getData()
    {
        $pembayaran = Pembayaran::with(['santri.gelombang'])->get();
        return response()->json($pembayaran);
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis' => 'required|in:administrasi,daftar_ulang,spp',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:pending,success,failed'
        ]);

        $pembayaran = Pembayaran::create($request->all());
        return response()->json($pembayaran, 201);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();
        return response()->json(null, 204);
    }

    public function getSingleData(Pembayaran $pembayaran)
    {
        $pembayaran->load('santri.gelombang', 'santri.orangTua');
        return response()->json($pembayaran);
    }

    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:bca,mandiri,bni,bri'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update([
            'status' => 'success',
            'payment_method' => $request->payment_method,
            'tanggal_konfirmasi' => now()
        ]);

        return response()->json($pembayaran);
    }
}
