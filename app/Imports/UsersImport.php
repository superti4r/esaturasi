<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Spatie\Permission\Models\Role;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $importedCount = 0;
    public int $skippedCount = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        $role = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);

        foreach ($rows as $row) {
            $nama = trim((string) ($row['nama'] ?? ''));
            $nip  = trim((string) ($row['nip']  ?? ''));
            $no   = trim((string) ($row['no']   ?? ''));

            // Skip jika nama kosong
            if (empty($nama)) {
                $this->skippedCount++;
                continue;
            }

            // Bersihkan NIP dari spasi
            $nipClean = preg_replace('/\s+/', '', $nip);

            // Skip jika NIP sudah ada
            if (!empty($nipClean) && User::where('nip', $nipClean)->exists()) {
                $this->skippedCount++;
                continue;
            }

            // Kode guru dari kolom NO, format GR001
            $kodeGuru = !empty($no) ? $no : null;

            // Password default = NIP bersih
            $password = !empty($nipClean) ? $nipClean : 'password123';

            $user = User::create([
                'nip'       => $nipClean ?: null,
                'kode_guru' => $kodeGuru,
                'gol'       => !empty($row['gol']) ? trim($row['gol']) : null,
                'name'      => $nama,
                'email'     => null,
                'password'  => bcrypt($password),
                'address'   => null,
            ]);

            $user->assignRole($role);

            $this->importedCount++;
        }
    }

    private function generateEmail(string $nama, string $nip): string
    {
        $namaBersih = strtolower(preg_replace('/[^a-zA-Z\s]/', '', $nama));
        $namaDepan  = explode(' ', trim($namaBersih))[0];
        $email      = $namaDepan . (!empty($nip) ? $nip : rand(100, 999)) . '@guru.sch.id';

        $counter = 1;
        $baseEmail = $email;
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter;
            $counter++;
        }

        return $email;
    }
}