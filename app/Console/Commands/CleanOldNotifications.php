<?php

namespace App\Console\Commands;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean {--days=30 : Hapus notifikasi yang lebih lama dari jumlah hari}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan notifikasi lama dari database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);
        
        $count = Activity::where('created_at', '<', $date)->count();
        
        if ($count > 0) {
            Activity::where('created_at', '<', $date)->delete();
            $this->info("Berhasil menghapus {$count} notifikasi lama (lebih dari {$days} hari).");
            Log::info("Scheduled task: Deleted {$count} old notifications (older than {$days} days).");
        } else {
            $this->info("Tidak ada notifikasi lama yang perlu dihapus.");
            Log::info("Scheduled task: No old notifications to delete.");
        }
        
        return 0;
    }
} 