<?php

namespace App\Imports;

use App\Models\SoalPosttest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalPosttestImport implements ToModel, WithHeadingRow
{
    protected $posttest_id;

    public function __construct($posttest_id)
    {
        $this->posttest_id = $posttest_id;
    }

    public function model(array $row)
{
    return new SoalPosttest([
        'posttest_id' => $this->posttest_id,
        'soal' => $row['soal'] ?? null,
        'opsi_a' => $row['opsi_a'] ?? null,
        'opsi_b' => $row['opsi_b'] ?? null,
        'opsi_c' => $row['opsi_c'] ?? null,
        'opsi_d' => $row['opsi_d'] ?? null,
        'jawaban' => strtoupper($row['jawaban'] ?? null),
        'poin' => $row['poin'] ?? 10, // default
    ]);
}
}