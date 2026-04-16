<?php

namespace App\Imports;

use App\Models\SoalPretest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalPretestImport implements ToModel, WithHeadingRow
{
    protected $pretest_id;

    public function __construct($pretest_id)
    {
        $this->pretest_id = $pretest_id;
    }

    public function model(array $row)
{
    return new SoalPretest([
        'pretest_id' => $this->pretest_id,
        'soal' => $row['soal'] ?? null,
        'opsi_a' => $row['opsi_a'] ?? null,
        'opsi_b' => $row['opsi_b'] ?? null,
        'opsi_c' => $row['opsi_c'] ?? null,
        'opsi_d' => $row['opsi_d'] ?? null,
        'jawaban' => strtoupper($row['jawaban'] ?? null),
    ]);
}
}