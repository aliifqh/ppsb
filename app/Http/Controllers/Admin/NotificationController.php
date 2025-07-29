<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $query = Activity::where('user_id', auth()->id());

        // Filter berdasarkan tipe notifikasi
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan waktu (default: 1 minggu terakhir)
        if (!$request->has('show_all') || $request->show_all !== 'true') {
            $query->recent();
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $types = Activity::select('type')
            ->where('user_id', auth()->id())
            ->distinct()
            ->pluck('type');

        $showAll = $request->has('show_all') && $request->show_all === 'true';

        return view('admin.notifications.index', compact('notifications', 'types', 'showAll'));
    }

    public function destroy($id)
    {
        try {
            $notification = Activity::findOrFail($id);

            if ($notification->user_id !== auth()->id()) {
                Log::warning('Unauthorized attempt to delete notification', [
                    'user_id' => auth()->id(),
                    'notification_id' => $id,
                    'notification_owner_id' => $notification->user_id,
                    'ip' => request()->ip()
                ]);

                abort(403);
            }

            $notification->delete();

            Log::info('User deleted notification', [
                'user_id' => auth()->id(),
                'notification_id' => $id,
                'ip' => request()->ip()
            ]);

            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting notification', [
                'user_id' => auth()->id(),
                'notification_id' => $id,
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus notifikasi');
        }
    }
}