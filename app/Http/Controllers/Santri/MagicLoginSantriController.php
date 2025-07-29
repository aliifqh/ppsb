<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SantriMagicToken;
use App\Models\Santri;
use Carbon\Carbon;

class MagicLoginSantriController extends Controller
{
    public function magicLogin($token)
    {
        $magic = SantriMagicToken::where('token', $token)->first();
        if (!$magic) {
            return redirect()->route('santri.check.form')->with('error', 'Link login tidak valid.');
        }
        if ($magic->used_at) {
            return redirect()->route('santri.check.form')->with('error', 'Link login sudah pernah digunakan.');
        }
        if (Carbon::now()->gt($magic->expired_at)) {
            return redirect()->route('santri.check.form')->with('error', 'Link login sudah expired.');
        }
        $santri = $magic->santri;
        if (!$santri) {
            return redirect()->route('santri.check.form')->with('error', 'Data santri tidak ditemukan.');
        }
        // Set session login santri
        session([
            'nomor_pendaftaran' => $santri->nomor_pendaftaran,
            'nama_santri' => $santri->nama,
            'is_santri_logged_in' => true
        ]);
        // Tandai token sudah dipakai
        $magic->used_at = Carbon::now();
        $magic->save();
        return redirect()->route('santri.dashboard.index', ['nomor_pendaftaran' => $santri->nomor_pendaftaran]);
    }
}
