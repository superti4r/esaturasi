<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

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
            $rowArray = $row->toArray();

            $nama     = trim((string) ($rowArray['nama'] ?? ''));
            $nip      = trim((string) ($rowArray['nip']  ?? ''));
            $kodeGuru = trim((string) ($rowArray['no']   ?? ''));

            // Ambil golongan dari semua kemungkinan key
            $gol = '';
            foreach ($rowArray as $key => $value) {
                if (strtolower(trim($key)) === 'gol') {
                    $gol = trim((string) $value);
                    break;
                }
            }

            if (empty($nama)) {
                $this->skippedCount++;
                continue;
            }

            if (!empty($nip) && User::where('nip', $nip)->exists()) {
                $this->skippedCount++;
                continue;
            }

            $email    = $this->generateUniqueEmail($nama);
            $password = !empty($nip) ? $nip : $email;

            $user = User::create([
                'name'      => $nama,
                'nip'       => $nip ?: null,
                'kode_guru' => $kodeGuru ?: null,
                'gol'  => $gol ?: null,
                'email'     => $email,
                'password'  => Hash::make($password),
            ]);

            $user->assignRole('guru');

            $this->importedCount++;
        }
    }

    private function generateUniqueEmail(string $nama): string
    {
        $base  = $this->extractBaseEmail($nama);
        $email = $base . '@guru.sch.id';

        if (!User::where('email', $email)->exists()) {
            return $email;
        }

        $counter = 2;
        do {
            $email = $base . $counter . '@guru.sch.id';
            $counter++;
        } while (User::where('email', $email)->exists());

        return $email;
    }

    private function extractBaseEmail(string $nama): string
    {
        $nama      = preg_replace('/^(Drs?\.|Dra\.|Dr\.|H\.|Hj\.)\s*/i', '', $nama);
        $namaDepan = explode(',', $nama)[0];
        $kataDepan = explode(' ', trim($namaDepan))[0];
        $kataDepan = preg_replace('/[^a-zA-Z0-9]/', '', $kataDepan);

        return strtolower($kataDepan);
    }
}