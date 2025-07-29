<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateSoalUjian implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            // Contoh Soal Pilihan Ganda 1
            [
                'Apa ibukota Indonesia?',
                'pilihan_ganda',
                1,
                1,
                '',
                'Jakarta',
                'Bandung',
                'Surabaya',
                'Medan',
                '',
                'A'
            ],
            // Contoh Soal Pilihan Ganda 2
            [
                'Siapa penemu bola lampu?',
                'pilihan_ganda',
                1,
                2,
                '',
                'Albert Einstein',
                'Thomas Edison',
                'Isaac Newton',
                'Nikola Tesla',
                '',
                'B'
            ],
            // Contoh Soal Essay 1
            [
                'Jelaskan pengertian Pancasila!',
                'essay',
                2,
                3,
                'Pancasila adalah dasar negara Indonesia yang terdiri dari 5 sila',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            // Contoh Soal Essay 2
            [
                'Sebutkan 5 sila dalam Pancasila!',
                'essay',
                2,
                4,
                '1. Ketuhanan Yang Maha Esa\n2. Kemanusiaan yang Adil dan Beradab\n3. Persatuan Indonesia\n4. Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan dalam Permusyawaratan/Perwakilan\n5. Keadilan Sosial bagi Seluruh Rakyat Indonesia',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            // Baris kosong untuk memisahkan contoh dengan area pengisian
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            // Area pengisian (10 baris kosong)
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Pertanyaan',
            'Jenis Soal',
            'Bobot Nilai',
            'Urutan',
            'Kunci Jawaban',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Pilihan E',
            'Kunci Jawaban Pilihan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling untuk header
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Styling untuk contoh soal
        $sheet->getStyle('A2:K5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2']
            ],
        ]);

        // Styling untuk area pengisian
        $sheet->getStyle('A7:K16')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ],
        ]);

        // Mengatur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(50); // Pertanyaan
        $sheet->getColumnDimension('B')->setWidth(20); // Jenis Soal
        $sheet->getColumnDimension('C')->setWidth(15); // Bobot Nilai
        $sheet->getColumnDimension('D')->setWidth(15); // Urutan
        $sheet->getColumnDimension('E')->setWidth(50); // Kunci Jawaban
        $sheet->getColumnDimension('F')->setWidth(30); // Pilihan A
        $sheet->getColumnDimension('G')->setWidth(30); // Pilihan B
        $sheet->getColumnDimension('H')->setWidth(30); // Pilihan C
        $sheet->getColumnDimension('I')->setWidth(30); // Pilihan D
        $sheet->getColumnDimension('J')->setWidth(30); // Pilihan E
        $sheet->getColumnDimension('K')->setWidth(20); // Kunci Jawaban Pilihan

        // Menambahkan komentar di setiap sel
        $sheet->getComment('A1')->getText()->createTextRun('Tulis pertanyaan soal di sini');
        $sheet->getComment('B1')->getText()->createTextRun('Isi dengan: pilihan_ganda atau essay');
        $sheet->getComment('C1')->getText()->createTextRun('Isi dengan angka (contoh: 1, 2, 3)');
        $sheet->getComment('D1')->getText()->createTextRun('Isi dengan angka urutan soal');
        $sheet->getComment('E1')->getText()->createTextRun('Untuk essay: isi kunci jawaban\nUntuk pilihan ganda: kosongkan');
        $sheet->getComment('F1')->getText()->createTextRun('Isi pilihan jawaban A\nUntuk essay: kosongkan');
        $sheet->getComment('G1')->getText()->createTextRun('Isi pilihan jawaban B\nUntuk essay: kosongkan');
        $sheet->getComment('H1')->getText()->createTextRun('Isi pilihan jawaban C\nUntuk essay: kosongkan');
        $sheet->getComment('I1')->getText()->createTextRun('Isi pilihan jawaban D\nUntuk essay: kosongkan');
        $sheet->getComment('J1')->getText()->createTextRun('Isi pilihan jawaban E (opsional)\nUntuk essay: kosongkan');
        $sheet->getComment('K1')->getText()->createTextRun('Untuk pilihan ganda: isi dengan A, B, C, D, atau E\nUntuk essay: kosongkan');

        // Menambahkan catatan
        $sheet->setCellValue('A18', 'CATATAN PENTING:');
        $sheet->setCellValue('A19', '1. Jenis Soal harus diisi dengan: pilihan_ganda atau essay');
        $sheet->setCellValue('A20', '2. Untuk soal pilihan ganda:');
        $sheet->setCellValue('A21', '   - Isi pilihan jawaban di kolom Pilihan A sampai Pilihan E');
        $sheet->setCellValue('A22', '   - Kolom Pilihan E boleh dikosongkan jika hanya ada 4 pilihan');
        $sheet->setCellValue('A23', '   - Kunci Jawaban Pilihan cukup tulis huruf A, B, C, D, atau E');
        $sheet->setCellValue('A24', '3. Untuk soal essay:');
        $sheet->setCellValue('A25', '   - Kunci Jawaban harus diisi dengan jawaban yang benar');
        $sheet->setCellValue('A26', '   - Kolom Pilihan A sampai E dan Kunci Jawaban Pilihan dikosongkan');
        $sheet->setCellValue('A27', '4. Bobot Nilai dan Urutan harus berupa angka');
        $sheet->setCellValue('A28', '5. Contoh pengisian bisa dilihat di baris 2-5');

        // Styling untuk catatan
        $sheet->getStyle('A18:A28')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF2CC']
            ],
        ]);

        return [];
    }
}
