<?php

namespace App\Helpers;

class FileNameHelper
{
    /**
     * Generate nama file dengan format: tahun_unit_nama_tipe.extension
     *
     * @param string $unit Unit pendaftaran (TKS, SMP, dll)
     * @param string $nama Nama santri
     * @param string $tipe Tipe dokumen (akta, pasfoto, dll)
     * @param string $extension Ekstensi file asli
     * @param int|null $tahun Tahun (opsional, default tahun sekarang)
     * @return string
     */
    public static function generate($unit, $nama, $tipe, $extension, $tahun = null)
    {
        // Jika tahun tidak diisi, gunakan tahun sekarang
        $tahun = $tahun ?? date('Y');

        // Bersihkan dan format nama
        $unit = strtolower($unit);
        $nama = strtolower(str_replace(' ', '', $nama));
        $tipe = strtolower($tipe);

        // Pastikan extension tidak mengandung titik
        $extension = ltrim($extension, '.');

        // Generate nama file
        return sprintf('%d_%s_%s_%s.%s',
            $tahun,
            $unit,
            $nama,
            $tipe,
            $extension
        );
    }
}
