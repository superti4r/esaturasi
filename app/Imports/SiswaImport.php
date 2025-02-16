<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            Siswa::create([
                'nisn' => $row['nisn'] ?? null,
                'nama' => $row['nama'] ?? null,
                'tanggal_lahir' => $this->convertDate($row['tanggal_lahir'] ?? '2000-01-01'),
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'alamat' => $row['alamat'] ?? null,
                'kelas_id' => $row['kelas_id'] ?? null,
                'jurusan_id' => $row['jurusan_id'] ?? null,
                'tahun_masuk' => $row['tahun_masuk'] ?? null,
                'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                'status' => $row['status'] ?? null,
                'email' => $row['email'] ?? null,
                'password' => isset($row['password']) ? bcrypt($row['password']) : bcrypt('default123'),
                'email_verified_at' => now(),
            ]);
        }
    }

    private function convertDate($date)
    {
        if (empty($date) || trim($date) == '') {
            return '2000-01-01';
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return '2000-01-01';
        }
    }
}
