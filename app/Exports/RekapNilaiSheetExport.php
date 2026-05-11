<?php

namespace App\Exports;

use App\Models\HasilPosttest;
use App\Models\HasilPretest;
use App\Models\Posttest;
use App\Models\Pretest;
use App\Models\SubmissionAndAssessment;
use App\Models\Task;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapNilaiSheetExport implements FromArray, WithTitle, WithStyles
{
    private array $sheetData = [];
    private int   $headerRow = 1;
    private int   $colCount  = 0;

    public function __construct(
        private readonly Collection $siswaList,
        private readonly array      $slugIds,
        private readonly string     $type,   // 'pretest' | 'tugas' | 'posttest'
        private readonly string     $title,
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function array(): array
    {
        // Ambil kolom (bab/tugas/posttest)
        $columns = match ($this->type) {
            'tugas'    => Task::whereIn('slug_id', $this->slugIds)->orderBy('created_at')->get()
                            ->map(fn ($t) => ['id' => $t->id, 'label' => $t->title]),
            'posttest' => Posttest::whereIn('slug_id', $this->slugIds)->orderBy('created_at')->get()
                            ->map(fn ($p) => ['id' => $p->id, 'label' => $p->judul]),
            default    => Pretest::whereIn('slug_id', $this->slugIds)->orderBy('created_at')->get()
                            ->map(fn ($p) => ['id' => $p->id, 'label' => $p->judul]),
        };

        // Ambil semua nilai sekaligus
        $nilaiMap = match ($this->type) {
            'tugas'    => SubmissionAndAssessment::whereIn('task_id', $columns->pluck('id'))
                            ->get()->groupBy('student_id')->map(fn ($i) => $i->keyBy('task_id')),
            'posttest' => HasilPosttest::whereIn('posttest_id', $columns->pluck('id'))
                            ->get()->groupBy('student_id')->map(fn ($i) => $i->keyBy('posttest_id')),
            default    => HasilPretest::whereIn('pretest_id', $columns->pluck('id'))
                            ->get()->groupBy('student_id')->map(fn ($i) => $i->keyBy('pretest_id')),
        };

        $this->colCount = 3 + $columns->count() + 1; // NO + NISN + NAMA + kolom nilai + RATA

        // Header
        $header = ['No', 'NISN', 'Nama Siswa'];
        foreach ($columns as $i => $col) {
            $header[] = 'T' . ($i + 1) . ' - ' . $col['label'];
        }
        $header[] = 'Rata-rata';

        $rows = [$header];

        // Data siswa
        foreach ($this->siswaList as $no => $siswa) {
            $nilaiSiswa = $nilaiMap->get($siswa->id, collect());
            $row        = [$no + 1, $siswa->nisn ?? '-', $siswa->name];
            $total      = 0;
            $count      = 0;

            foreach ($columns as $col) {
                $record = $nilaiSiswa->get($col['id']);
                $nilai  = match ($this->type) {
                    'tugas'  => $record?->assignment,
                    default  => $record?->nilai,
                };
                $row[] = $nilai ?? '-';
                if ($nilai !== null) {
                    $total += $nilai;
                    $count++;
                }
            }

            $row[] = $count > 0 ? round($total / $count) : '-';
            $rows[] = $row;
        }

        $this->sheetData = $rows;
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->colCount);
        $lastRow = count($this->sheetData);

        // Style header
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Border semua data
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Auto width semua kolom
        foreach (range(1, $this->colCount) as $i) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }
}