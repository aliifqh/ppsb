<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Santri;

class DateHelper
{
    public static function toIndonesianFormat($date)
    {
        if (!$date) return null;

        try {
            // Pastikan timezone diatur ke Asia/Jakarta
            $date = $date instanceof Carbon ? $date : Carbon::parse($date)->timezone('Asia/Jakarta');
            $bulanIndonesia = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];

            $tanggal = ltrim($date->format('d'), '0');
            $bulan = $bulanIndonesia[$date->format('m')] ?? '';
            $tahun = $date->format('Y');

            return "{$tanggal} {$bulan} {$tahun}";
        } catch (\Exception $e) {
            \Log::error('Error konversi ke format Indonesia', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            return $date;
        }
    }

    public static function toIndonesianFormatWithTime($date)
    {
        if (!$date) return null;

        try {
            // Pastikan timezone diatur ke Asia/Jakarta
            $date = $date instanceof Carbon ? $date->timezone('Asia/Jakarta') : Carbon::parse($date)->timezone('Asia/Jakarta');
            $bulanIndonesia = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];

            $tanggal = ltrim($date->format('d'), '0');
            $bulan = $bulanIndonesia[$date->format('m')] ?? '';
            $tahun = $date->format('Y');
            $waktu = $date->format('H:i');

            return "{$tanggal} {$bulan} {$tahun}, {$waktu} WIB";
        } catch (\Exception $e) {
            \Log::error('Error konversi ke format Indonesia dengan waktu', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            return $date;
        }
    }

    public static function formatDateTime($date)
    {
        if (!$date) return null;
        
        try {
            // Pastikan timezone diatur ke Asia/Jakarta
            $date = $date instanceof Carbon ? $date->timezone('Asia/Jakarta') : Carbon::parse($date)->timezone('Asia/Jakarta');
            return $date->format('d/m/Y H:i') . ' WIB';
        } catch (\Exception $e) {
            \Log::error('Error format tanggal dan waktu', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            return $date;
        }
    }

    public static function fromIndonesianFormat($date)
    {
        if (!$date) return null;

        try {
            $bulanIndonesia = [
                'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
                'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
                'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12',
                'jan' => '01', 'feb' => '02', 'mar' => '03', 'apr' => '04',
                'jun' => '06', 'jul' => '07', 'agu' => '08',
                'sep' => '09', 'okt' => '10', 'nov' => '11', 'des' => '12'
            ];

            // Jika sudah dalam format Y-m-d
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                if (Carbon::createFromFormat('Y-m-d', $date)->isValid()) {
                    return $date;
                }
            }

            if (preg_match('/^(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})$/', $date, $matches)) {
                $hari = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $bulan = $bulanIndonesia[strtolower($matches[2])] ?? null;
                $tahun = $matches[3];

                if (!$bulan) {
                    throw new \Exception('Format bulan tidak valid. Gunakan nama bulan dalam Bahasa Indonesia (contoh: Juni)');
                }

                if (!checkdate((int)$bulan, (int)$hari, (int)$tahun)) {
                    throw new \Exception('Tanggal tidak valid untuk bulan yang dipilih');
                }

                return "{$tahun}-{$bulan}-{$hari}";
            }

            throw new \Exception('Format tanggal tidak valid. Gunakan format: DD Bulan YYYY (contoh: 29 Juni 2005)');
        } catch (\Exception $e) {
            \Log::error('Error konversi dari format Indonesia', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public static function validateAge($date)
    {
        try {
            $birthDate = $date instanceof Carbon ? $date : Carbon::parse($date);
            $age = $birthDate->age;

            if ($age < 10 || $age > 25) {
                throw new \Exception('Usia harus antara 10-25 tahun.');
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error validasi usia', [
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Convert tanggal dari format "DD bulan YYYY" ke "YYYY-MM-DD"
     */
    public static function convertToDate($tanggal)
    {
        $bulan = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12'
        ];

        // Parse tanggal
        $parts = explode(' ', strtolower($tanggal));
        if (count($parts) !== 3) {
            throw new \Exception('Format tanggal tidak valid');
        }

        $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        $month = $bulan[$parts[1]] ?? null;
        $year = $parts[2];

        if (!$month) {
            throw new \Exception('Nama bulan tidak valid');
        }

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day")->format('Y-m-d');
    }

    public static function formatIndonesian($date)
    {
        if (!$date) return null;

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $date = Carbon::parse($date);
        return $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }
}
