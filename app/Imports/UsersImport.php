<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama      = trim((string) ($row['nama'] ?? ''));
            $nip       = trim((string) ($row['nip']  ?? ''));
            $kodeGuru  = trim((string) ($row['no']   ?? ''));

            if (empty($nama) || empty($nip)) {
                $this->skippedCount++;
                continue;
            }

            if (User::where('nip', $nip)->exists()) {
                $this->skippedCount++;
                continue;
            }

            User::create([
                'name'      => $nama,
                'nip'       => $nip,
                'kode_guru' => $kodeGuru ?: null,
                'golongan'  => !empty($row['gol']) ? trim($row['gol']) : null,
                'email'     => strtolower(str_replace(' ', '.', $nama)) . '@guru.sch.id',
                'password'  => Hash::make($nip),
            ]);

            $this->importedCount++;
        }
    }
}