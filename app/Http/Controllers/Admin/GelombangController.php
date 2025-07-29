<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use App\Helpers\DateHelper;

class GelombangController extends Controller
{
    public function index()
    {
        $gelombang = Gelombang::latest()->get();
        return view('admin.gelombang.index', compact('gelombang'));
    }

    public function create()
    {
        return view('admin.gelombang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'boolean',
            'biaya_administrasi' => 'required|string',
            'biaya_daftar_ulang' => 'required|string',
        ]);

        // Konversi format rupiah ke angka
        $biayaAdministrasi = (int) str_replace(['.', ','], '', $request->biaya_administrasi);
        $biayaDaftarUlang = (int) str_replace(['.', ','], '', $request->biaya_daftar_ulang);

        Gelombang::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status ?? false,
            'biaya_administrasi' => $biayaAdministrasi,
            'biaya_daftar_ulang' => $biayaDaftarUlang,
        ]);

        return redirect()->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil ditambahkan');
    }

    public function edit(Gelombang $gelombang)
    {
        return view('admin.gelombang.edit', compact('gelombang'));
    }

    public function show(Gelombang $gelombang)
    {
        return view('admin.gelombang.show', compact('gelombang'));
    }

    public function update(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'boolean',
            'biaya_administrasi' => 'required|string',
            'biaya_daftar_ulang' => 'required|string',
        ]);

        // Konversi format rupiah ke angka
        $biayaAdministrasi = (int) str_replace(['.', ','], '', $request->biaya_administrasi);
        $biayaDaftarUlang = (int) str_replace(['.', ','], '', $request->biaya_daftar_ulang);

        $gelombang->update([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status ?? false,
            'biaya_administrasi' => $biayaAdministrasi,
            'biaya_daftar_ulang' => $biayaDaftarUlang,
        ]);

        return redirect()->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil diperbarui');
    }

    public function toggleStatus(Gelombang $gelombang)
    {
        if ($gelombang->isExpired()) {
            return redirect()->route('admin.gelombang.index')
                ->with('error', 'Gelombang yang sudah kadaluarsa tidak dapat diaktifkan kembali.');
        }

        $gelombang->update(['status' => !$gelombang->status]);

        $statusText = $gelombang->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.gelombang.index')
            ->with('success', "Status gelombang berhasil {$statusText}");
    }

    public function destroy(Gelombang $gelombang)
    {
        if (!$gelombang->canBeDeleted()) {
            return redirect()->route('admin.gelombang.index')
                ->with('error', 'Gelombang tidak dapat dihapus karena masih memiliki santri yang belum melunasi pembayaran');
        }

        $gelombang->delete();

        return redirect()->route('admin.gelombang.index')
            ->with('success', 'Gelombang berhasil dihapus');
    }

    // API: List all gelombang
    public function apiIndex()
    {
        $gelombang = Gelombang::latest()->get();
        return response()->json($gelombang);
    }

    // API: Store new gelombang
    public function apiStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'boolean',
            'biaya_administrasi' => 'required|string',
            'biaya_daftar_ulang' => 'required|string',
        ]);
        $biayaAdministrasi = (int) str_replace(['.', ','], '', $request->biaya_administrasi);
        $biayaDaftarUlang = (int) str_replace(['.', ','], '', $request->biaya_daftar_ulang);
        $gelombang = Gelombang::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status ?? false,
            'biaya_administrasi' => $biayaAdministrasi,
            'biaya_daftar_ulang' => $biayaDaftarUlang,
        ]);
        return response()->json($gelombang, 201);
    }

    // API: Show gelombang by id
    public function apiShow(Gelombang $gelombang)
    {
        return response()->json($gelombang);
    }

    // API: Update gelombang
    public function apiUpdate(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'boolean',
            'biaya_administrasi' => 'required|string',
            'biaya_daftar_ulang' => 'required|string',
        ]);
        $biayaAdministrasi = (int) str_replace(['.', ','], '', $request->biaya_administrasi);
        $biayaDaftarUlang = (int) str_replace(['.', ','], '', $request->biaya_daftar_ulang);
        $gelombang->update([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status ?? false,
            'biaya_administrasi' => $biayaAdministrasi,
            'biaya_daftar_ulang' => $biayaDaftarUlang,
        ]);
        return response()->json($gelombang);
    }

    // API: Delete gelombang
    public function apiDestroy(Gelombang $gelombang)
    {
        if (!$gelombang->canBeDeleted()) {
            return response()->json(['error' => 'Gelombang tidak dapat dihapus karena masih memiliki santri yang belum melunasi pembayaran'], 422);
        }
        $gelombang->delete();
        return response()->json(['message' => 'Gelombang berhasil dihapus']);
    }
}
