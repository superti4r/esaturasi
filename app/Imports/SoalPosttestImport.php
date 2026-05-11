<?php

namespace App\Imports;

use App\Models\SoalPosttest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class SoalPosttestImport implements ToModel, WithHeadingRow, WithEvents
{
    protected $posttest_id;

    public function __construct($posttest_id)
    {
        $this->posttest_id = $posttest_id;
    }

    /**
     * ✅ Hapus semua soal lama sebelum import Excel baru
     * Ini yang menyebabkan soal yang dihapus balik lagi
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function () {
                SoalPosttest::where('posttest_id', $this->posttest_id)->delete();
            },
        ];
    }

    public function model(array $row)
    {
        // Skip baris kosong
        if (empty($row['soal'])) return null;

        return new SoalPosttest([
            'posttest_id' => $this->posttest_id,
            'soal'        => $row['soal']   ?? null,
            'opsi_a'      => $row['opsi_a'] ?? null,
            'opsi_b'      => $row['opsi_b'] ?? null,
            'opsi_c'      => $row['opsi_c'] ?? null,
            'opsi_d'      => $row['opsi_d'] ?? null,
            'jawaban'     => strtoupper($row['jawaban'] ?? ''),
            'poin'        => $row['poin']   ?? 10,
        ]);
    }
}