<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\LocationHelper;

class SantriExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    // Warna tema dari formulir
    private $primaryColor = '2A6061';
    private $secondaryColor = '70CACB';
    private $lightGray = 'F0F0F0';

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Santri::with(['orangTua', 'dokumen', 'pembayaran'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $dokPendukungHeaders = [];
        for ($i = 1; $i <= 5; $i++) {
            $dokPendukungHeaders[] = 'Dokumen Pendukung ' . $i;
        }
        return [
            ['DATA SANTRI BARU PONDOK PESANTREN', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['Diekspor pada: ' . now()->format('d F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            array_merge([
                'ID',
                'Nomor Pendaftaran',
                'Nama',
                'NISN',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Asal Sekolah',
                'Anak Ke',
                'Jumlah Saudara',
                'Kelas',
                'Unit',
                'Status Pembayaran',
                'Status Tes',
                'Nama Ayah',
                'Pekerjaan Ayah',
                'Pekerjaan Ayah Lainnya',
                'Pendidikan Ayah',
                'WA Ayah',
                'Nama Ibu',
                'Pekerjaan Ibu',
                'Pekerjaan Ibu Lainnya',
                'Pendidikan Ibu',
                'WA Ibu',
                'Provinsi',
                'Kabupaten',
                'Kecamatan',
                'Desa',
                'Alamat',
                'Kode Pos',
                'Pasfoto',
                'Akta Lahir',
                'Kartu Keluarga',
                'Ijazah',
            ], $dokPendukungHeaders)
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        try {
            $tanggal_lahir = null;
            if ($row->tanggal_lahir) {
                // Jika format sudah Y-m-d, konversi ke format Indonesia
                if (preg_match('/^\\d{4}-\\d{2}-\\d{2}/', $row->tanggal_lahir)) {
                    $tanggal_lahir = Carbon::parse($row->tanggal_lahir)->translatedFormat('d F Y');
                } else {
                    $tanggal_lahir = DateHelper::fromIndonesianFormat($row->tanggal_lahir);
                }
            }

            // Ambil data dari relasi jika ada
            $orangTua = $row->orangTua;
            $dokumen = $row->dokumen;
            $pembayaran = $row->pembayaran;

            // Dokumen pendukung (maksimal 5 kolom)
            $dokPendukung = [];
            if ($dokumen && is_array($dokumen->dokumen_pendukung)) {
                foreach ($dokumen->dokumen_pendukung as $file) {
                    $dokPendukung[] = $file;
                }
            }
            // Pastikan selalu 5 kolom
            for ($i = count($dokPendukung); $i < 5; $i++) {
                $dokPendukung[] = '';
            }
            $dokPendukung = array_slice($dokPendukung, 0, 5);

            return array_merge([
                $row->id,
                $row->nomor_pendaftaran,
                $row->nama,
                $row->nisn,
                $row->jenis_kelamin,
                $row->tempat_lahir,
                $tanggal_lahir,
                $row->asal_sekolah,
                $row->anak_ke,
                $row->jumlah_saudara,
                $row->kelas ?? '',
                strtoupper($row->unit),
                $pembayaran->status ?? $row->status_pembayaran ?? '',
                $row->status_tes,
                $orangTua->nama_ayah ?? $row->nama_ayah ?? '',
                $orangTua->pekerjaan_ayah ?? $row->pekerjaan_ayah ?? '',
                $orangTua->pekerjaan_ayah_lainnya ?? $row->pekerjaan_ayah_lainnya ?? '',
                $orangTua->pendidikan_ayah ?? $row->pendidikan_ayah ?? '',
                $orangTua->wa_ayah ?? $row->wa_ayah ?? '',
                $orangTua->nama_ibu ?? $row->nama_ibu ?? '',
                $orangTua->pekerjaan_ibu ?? $row->pekerjaan_ibu ?? '',
                $orangTua->pekerjaan_ibu_lainnya ?? $row->pekerjaan_ibu_lainnya ?? '',
                $orangTua->pendidikan_ibu ?? $row->pendidikan_ibu ?? '',
                $orangTua->wa_ibu ?? $row->wa_ibu ?? '',
                LocationHelper::getProvinceName($orangTua->provinsi_id ?? $row->provinsi_id ?? ''),
                LocationHelper::getRegencyName($orangTua->kabupaten_id ?? $row->kabupaten_id ?? ''),
                LocationHelper::getDistrictName($orangTua->kecamatan_id ?? $row->kecamatan_id ?? ''),
                LocationHelper::getVillageName($orangTua->desa_id ?? $row->desa_id ?? ''),
                $orangTua->alamat ?? $row->alamat ?? '',
                $orangTua->kode_pos ?? $row->kode_pos ?? '',
                $dokumen->pasfoto ?? '',
                $dokumen->akta_lahir ?? '',
                $dokumen->kartu_keluarga ?? '',
                $dokumen->ijazah ?? '',
            ], $dokPendukung);
        } catch (\Exception $e) {
            \Log::error('Error in SantriExport map: ' . $e->getMessage(), [
                'santri_id' => $row->id,
                'tanggal_lahir' => $row->tanggal_lahir
            ]);
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            // Kolom ID dan Nomor
            'A' => 6,      // ID (lebih ringkas)
            'B' => 18,     // Nomor Pendaftaran
            'D' => 12,     // NISN

            // Data Pribadi Santri
            'C' => 35,     // Nama (diperlebar untuk nama panjang)
            'E' => 13,     // Jenis Kelamin
            'F' => 20,     // Tempat Lahir
            'G' => 12,     // Tanggal Lahir
            'H' => 30,     // Asal Sekolah
            'I' => 8,      // Anak Ke
            'J' => 8,      // Jumlah Saudara
            'K' => 8,      // Kelas
            'L' => 8,      // Unit

            // Status
            'M' => 15,     // Status Pembayaran
            'N' => 12,     // Status Tes

            // Data Ayah
            'O' => 35,     // Nama Ayah
            'P' => 20,     // Pekerjaan Ayah
            'Q' => 20,     // Pekerjaan Ayah Lainnya
            'R' => 15,     // Pendidikan Ayah
            'S' => 15,     // WA Ayah

            // Data Ibu
            'T' => 35,     // Nama Ibu
            'U' => 20,     // Pekerjaan Ibu
            'V' => 20,     // Pekerjaan Ibu Lainnya
            'W' => 15,     // Pendidikan Ibu
            'X' => 15,     // WA Ibu

            // Alamat
            'Y' => 18,    // Provinsi
            'Z' => 25,    // Kabupaten
            'AA' => 25,    // Kecamatan
            'AB' => 25,    // Desa
            'AC' => 45,    // Alamat (diperlebar untuk alamat lengkap)
            'AD' => 10,    // Kode Pos

            // Dokumen
            'AE' => 15,    // Pasfoto
            'AF' => 15,    // Akta Lahir
            'AG' => 15,    // Kartu Keluarga
            'AH' => 15,    // Ijazah
            'AI' => 20,    // Dokumen Pendukung 1
            'AJ' => 20,    // Dokumen Pendukung 2
            'AK' => 20,    // Dokumen Pendukung 3
            'AL' => 20,    // Dokumen Pendukung 4
            'AM' => 20,    // Dokumen Pendukung 5
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk judul
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => $this->primaryColor],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        // Style untuk sub judul
        $subtitleStyle = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => $this->secondaryColor],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => $this->primaryColor],
                'endColor' => ['rgb' => $this->secondaryColor],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ];

        return [
            1 => $titleStyle,
            2 => $subtitleStyle,
            4 => $headerStyle,
            'A1:AM1' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A2:AM2' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge cells untuk judul
                $sheet->mergeCells('A1:AM1');
                $sheet->mergeCells('A2:AM2');

                // Set tinggi baris
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(40);
                $sheet->getDelegate()->getRowDimension(2)->setRowHeight(25);
                $sheet->getDelegate()->getRowDimension(3)->setRowHeight(10);
                $sheet->getDelegate()->getRowDimension(4)->setRowHeight(35);

                // Style untuk seluruh konten
                $lastColumn = 'AM';
                $lastRow = $sheet->getDelegate()->getHighestRow();

                // Style default untuk seluruh sel
                $sheet->getDelegate()->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $this->secondaryColor],
                        ],
                    ],
                ]);

                // Warna baris selang-seling
                for ($row = 5; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getDelegate()->getStyle('A'.$row.':'.$lastColumn.$row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $this->lightGray],
                            ],
                        ]);
                    }
                }

                // Style untuk kolom status dan numerik
                $numericColumns = ['A', 'I', 'J', 'K'];
                foreach ($numericColumns as $col) {
                    $sheet->getDelegate()->getStyle($col.'5:'.$col.$lastRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                // Style untuk kolom status
                $statusColumns = ['L', 'M', 'N'];
                foreach ($statusColumns as $col) {
                    $sheet->getDelegate()->getStyle($col.'5:'.$col.$lastRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }

                // Set minimum row height untuk data
                for ($row = 5; $row <= $lastRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(25);
                }

                // Freeze pane
                $sheet->getDelegate()->freezePane('A5');

                // Auto filter
                $sheet->getDelegate()->setAutoFilter('A4:' . $lastColumn . '4');
            },
        ];
    }
}
