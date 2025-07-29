<?php

namespace App\Http\Controllers\Santri;

use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class SantriPrintController extends Controller
{
    public function print(Santri $santri)
    {
        $data = [
            'santri' => $santri->load(['orangTua', 'dokumen']),
            'title' => 'Data Santri - ' . $santri->nama
        ];

        $pdf = PDF::loadView('prints.santri', $data);

        return $pdf->stream('data-santri-' . $santri->nomor_pendaftaran . '.pdf');
    }
}
