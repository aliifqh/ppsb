<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;

class UpdateBuktiPembayaranPath extends Command
{
    protected $signature = 'update:bukti-pembayaran-path';
    protected $description = 'Update path bukti pembayaran dari public/bukti-administrasi ke bukti-administrasi';

    public function handle()
    {
        $this->info('Memulai update path bukti pembayaran...');

        // Update path bukti administrasi
        $pembayaran = Pembayaran::where('bukti_biaya_administrasi', 'like', 'public/bukti-administrasi/%')->get();
        foreach ($pembayaran as $p) {
            $newPath = str_replace('public/bukti-administrasi/', 'bukti-administrasi/', $p->bukti_biaya_administrasi);
            $p->update(['bukti_biaya_administrasi' => $newPath]);
            $this->info("Updated: {$p->bukti_biaya_administrasi} -> {$newPath}");
        }

        // Update path bukti daftar ulang
        $pembayaran = Pembayaran::where('bukti_biaya_daftar_ulang', 'like', 'public/bukti-daftar-ulang/%')->get();
        foreach ($pembayaran as $p) {
            $newPath = str_replace('public/bukti-daftar-ulang/', 'bukti-daftar-ulang/', $p->bukti_biaya_daftar_ulang);
            $p->update(['bukti_biaya_daftar_ulang' => $newPath]);
            $this->info("Updated: {$p->bukti_biaya_daftar_ulang} -> {$newPath}");
        }

        $this->info('Selesai update path bukti pembayaran!');
    }
}
