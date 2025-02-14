<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SiswaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Siswa::select(
            'nisn',
            'nama',
            'tanggal_lahir',
            'tempat_lahir',
            'kelas_id',
            'jurusan_id',
            'jenis_kelamin',
            'alamat',
            'tahun_masuk',
            'status',
            'email',
            'created_at',
            'updated_at'
        )
        ->with(['kelas', 'jurusan'])
        ->get()
        ->map(function ($siswa) {
            return [
                'NISN' => $siswa->nisn,
                'Nama' => $siswa->nama,
                'Tanggal Lahir' => $siswa->tanggal_lahir,
                'Tempat Lahir' => $siswa->tempat_lahir,
                'Kelas' => $siswa->kelas->nama_kelas ?? '-',
                'Jurusan' => $siswa->jurusan->nama_jurusan ?? '-',
                'Jenis Kelamin' => ucfirst($siswa->jenis_kelamin),
                'Alamat' => $siswa->alamat,
                'Tahun Masuk' => $siswa->tahun_masuk,
                'Status' => ucfirst($siswa->status),
                'Email' => $siswa->email,
                'Dibuat Pada' => $siswa->created_at->format('d-m-Y H:i'),
                'Diperbarui Pada' => $siswa->updated_at->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Kelas',
            'Jurusan',
            'Jenis Kelamin',
            'Alamat',
            'Tahun Masuk',
            'Status',
            'Email',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0073E6']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:M$highestRow")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ],
        ]);

        $sheet->getStyle("A2:A$highestRow")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E2:E$highestRow")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("F2:F$highestRow")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("G2:G$highestRow")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("J2:J$highestRow")->getAlignment()->setHorizontal('center');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 15,
            'D' => 20,
            'E' => 10,
            'F' => 15,
            'G' => 15,
            'H' => 30,
            'I' => 12,
            'J' => 12,
            'K' => 25,
            'L' => 18,
            'M' => 18,
        ];
    }
}

