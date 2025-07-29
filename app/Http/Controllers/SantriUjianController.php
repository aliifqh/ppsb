<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SantriUjianController extends Controller
{
    public function index()
    {
        // Ambil data santri yang login
        $santri = Auth::user();

        // Data progress ujian (bisa dari database nanti)
        $progress = [
            'tes_lisan' => 60,
            'tes_tulis' => 40,
            'tes_psikotes' => 20,
            'tes_kesehatan' => 0,
        ];

        // Data tes lisan
        $tesLisan = [
            'bacaan_alquran' => [
                'nama' => "Tes Bacaan Al-Qur'an",
                'deskripsi' => 'Live talaqqi via Jitsi Meet',
                'status' => 'pending'
            ],
            'hafalan_alquran' => [
                'nama' => 'Tes Hafalan Al-Qur\'an',
                'deskripsi' => 'Live hafalan via Jitsi Meet',
                'status' => 'completed'
            ],
            'wawancara' => [
                'nama' => 'Tes Wawancara',
                'deskripsi' => 'Live wawancara via Jitsi Meet',
                'status' => 'pending'
            ]
        ];

        // Data tes tulis
        $tesTulis = [
            'matematika' => [
                'nama' => 'Tes Matematika',
                'durasi' => 120,
                'status' => 'pending',
                'progress' => 0
            ],
            'bahasa_indonesia' => [
                'nama' => 'Tes Bahasa Indonesia',
                'durasi' => 90,
                'status' => 'completed',
                'progress' => 100
            ],
            'bahasa_arab' => [
                'nama' => 'Tes Bahasa Arab',
                'durasi' => 60,
                'status' => 'pending',
                'progress' => 0
            ]
        ];

        // Data tes psikotes
        $tesPsikotes = [
            'mbti' => [
                'nama' => 'Tes MBTI',
                'status' => 'completed',
                'hasil' => 'INTJ',
                'deskripsi' => 'Architect - Imaginative and strategic thinkers'
            ],
            'disc' => [
                'nama' => 'Tes DISC',
                'status' => 'completed',
                'hasil' => 'DC',
                'deskripsi' => 'Dominant & Conscientious - Direct and systematic'
            ]
        ];

        // Data tes kesehatan
        $tesKesehatan = [
            'jadwal' => '2024-01-15 08:00',
            'lokasi' => 'Rumah Sakit Islam Jakarta',
            'alamat' => 'Jl. Cempaka Putih Tengah No.1, Jakarta Pusat',
            'status' => 'pending',
            'catatan' => 'Bawa kartu keluarga dan surat pengantar'
        ];

        return view('santri.ujian.index', [
            'santri' => $santri,
            'progress' => $progress,
            'tesLisanData' => $tesLisan,
            'tesTulisData' => $tesTulis,
            'tesPsikotesData' => $tesPsikotes,
            'tesKesehatanData' => $tesKesehatan,
        ]);
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'jenis_tes' => 'required|string',
            'progress' => 'required|integer|min:0|max:100'
        ]);

        // Update progress di database (implementasi nanti)
        // Untuk sementara return response

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil diupdate!',
            'data' => [
                'jenis_tes' => $request->jenis_tes,
                'progress' => $request->progress
            ]
        ]);
    }

    public function joinJitsi(Request $request)
    {
        $request->validate([
            'jenis_tes' => 'required|string',
            'room_name' => 'required|string'
        ]);

        // Generate Jitsi Meet URL
        $roomName = $request->room_name;
        $jitsiUrl = "https://meet.jit.si/{$roomName}";

        return response()->json([
            'success' => true,
            'jitsi_url' => $jitsiUrl,
            'room_name' => $roomName
        ]);
    }
}
