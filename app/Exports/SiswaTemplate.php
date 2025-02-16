<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SiswaTemplate implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['1234567890', 'Nama Siswa', '2005-06-15', 'Jember', 'Jl. Contoh No.1', 1, 1, 2022, 'Laki-laki', 'Aktif', 'email@example.com', 'password123'],
        ];
    }

    public function headings(): array
    {
        return [
            'NISN', 'Nama', 'Tanggal Lahir (YYYY-MM-DD)', 'Tempat Lahir', 'Alamat',
            'Kelas ID', 'Jurusan ID', 'Tahun Masuk', 'Jenis Kelamin', 'Status',
            'Email', 'Password'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $lastRow = count($this->array()) + 1;

        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A1:L$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
