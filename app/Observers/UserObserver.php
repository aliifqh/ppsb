<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "deleting" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = User::role('Super Admin')->count();

            if ($superAdminCount <= 1) {
                Log::warning('Pencegahan penghapusan: Upaya untuk menghapus Super Admin terakhir.', ['user_id' => $user->id]);
                // Melempar exception akan menghentikan proses delete
                throw new \Exception('Tidak dapat menghapus satu-satunya Super Admin di sistem.');
            }
        }
    }
}
