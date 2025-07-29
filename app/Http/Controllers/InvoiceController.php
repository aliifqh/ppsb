<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $nomor_pendaftaran
     * @param  string  $jenis
     * @return \Illuminate\Contracts\View\View
     */
    public function show($nomor_pendaftaran, $jenis)
    {
        $santri = Santri::where('nomor_pendaftaran', $nomor_pendaftaran)->firstOrFail();

        $pembayaran = $santri->pembayaran;

        if (!$pembayaran) {
            abort(404, 'Data pembayaran untuk santri ini tidak ditemukan.');
        }

        // Kita teruskan variabel $isPublic untuk menandakan ini adalah akses publik
        return view('admin.pembayaran.print', [
            'pembayaran' => $pembayaran,
            'jenis' => $jenis,
            'isPublic' => true
        ]);
    }
}
