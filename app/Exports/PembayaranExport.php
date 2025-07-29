<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    public function collection()
    {
        return Pembayaran::with(['santri.gelombang', 'santri.orangTua'])->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pendaftaran',
            'Nama Santri',
            'Unit',
            'Gelombang',
            'Biaya Administrasi',
            'Status Administrasi',
            'Tanggal Administrasi',
            'Biaya Daftar Ulang',
            'Status Daftar Ulang',
            'Tanggal Daftar Ulang',
            'Total Pembayaran'
        ];
    }

    public function map($pembayaran): array
    {
        $biayaAdministrasi = $pembayaran->santri->gelombang->biaya_administrasi ?? 0;
        $biayaDaftarUlang = $pembayaran->santri->gelombang->biaya_daftar_ulang ?? 0;
        
        return [
            $pembayaran->santri->nomor_pendaftaran,
            $pembayaran->santri->nama,
            strtoupper($pembayaran->santri->unit),
            $pembayaran->santri->gelombang->nama ?? '-',
            'Rp ' . number_format($biayaAdministrasi, 0, ',', '.'),
            ucwords(str_replace('_', ' ', $pembayaran->status_administrasi)),
            $pembayaran->tanggal_administrasi ? $pembayaran->tanggal_administrasi->format('d/m/Y H:i') : '-',
            'Rp ' . number_format($biayaDaftarUlang, 0, ',', '.'),
            ucwords(str_replace('_', ' ', $pembayaran->status_daftar_ulang)),
            $pembayaran->tanggal_daftar_ulang ? $pembayaran->tanggal_daftar_ulang->format('d/m/Y H:i') : '-',
            'Rp ' . number_format($biayaAdministrasi + $biayaDaftarUlang, 0, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2A6061'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk seluruh data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:K' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk kolom nominal (biaya)
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('K2:K' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Zebra striping untuk baris
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':K' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }

        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(30);
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(25);
        }

        // Wrap text untuk kolom tanggal
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('J2:J' . $lastRow)->getAlignment()->setWrapText(true);

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Nomor Pendaftaran
            'B' => 30, // Nama Santri
            'C' => 15, // Unit
            'D' => 20, // Gelombang
            'E' => 20, // Biaya Administrasi
            'F' => 20, // Status Administrasi
            'G' => 20, // Tanggal Administrasi
            'H' => 20, // Biaya Daftar Ulang
            'I' => 20, // Status Daftar Ulang
            'J' => 20, // Tanggal Daftar Ulang
            'K' => 20, // Total Pembayaran
        ];
    }

    public function title(): string
    {
        return 'Data Pembayaran Santri';
    }
} 