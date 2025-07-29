<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gelombang;
use Carbon\Carbon;

class CheckGelombangStatus extends Command
{
    protected $signature = 'gelombang:check-status';
    protected $description = 'Check and update gelombang status based on start and end date';

    public function handle()
    {
        $this->info('Memeriksa status gelombang...');

        // Nonaktifkan gelombang yang sudah berakhir
        $expiredGelombangs = Gelombang::where('status', true)
            ->get()
            ->filter(function($gelombang) {
                return $gelombang->isExpired();
            });

        foreach ($expiredGelombangs as $gelombang) {
            $gelombang->status = false;
            $gelombang->save();
            $this->info("Gelombang {$gelombang->nama} telah dinonaktifkan (kadaluarsa).");
        }

        // Aktifkan gelombang yang dalam rentang waktu aktif
        $activeGelombangs = Gelombang::where('status', false)
            ->get()
            ->filter(function($gelombang) {
                return $gelombang->isActive();
            });

        foreach ($activeGelombangs as $gelombang) {
            $gelombang->status = true;
            $gelombang->save();
            $this->info("Gelombang {$gelombang->nama} telah diaktifkan (dalam rentang waktu).");
        }

        $this->info('Pengecekan status gelombang selesai.');
    }
}
