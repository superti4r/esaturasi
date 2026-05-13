<?php

namespace App\Imports;

use App\Models\Archive;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        $activeArchive = Archive::where('status', 'Active')->first();

        foreach ($rows as $row) {
            $nama = trim((string) ($row['nama'] ?? ''));
            $nisn = trim((string) ($row['nisn'] ?? ''));

            // Skip jika nama atau NISN kosong
            if (empty($nama) || empty($nisn)) {
                $this->skippedCount++;
                continue;
            }

            // Skip jika NISN sudah ada
            if (Student::where('nisn', $nisn)->exists()) {
                $this->skippedCount++;
                continue;
            }

            // Cari kelas berdasarkan nama rombel
            $rombelNama  = trim((string) ($row['rombel_saat_ini'] ?? ''));
            $classroom   = Classroom::where('name', $rombelNama)->first();

            // Tanggal lahir
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalLahir = null;
                }
            }

            Student::create([
                'nisn'          => $nisn,
                'nipd'          => !empty($row['nipd']) ? trim($row['nipd']) : null,
                'name'          => $nama,
                'gender'        => strtoupper(trim((string) ($row['jk'] ?? ''))) === 'L' ? 'Male' : 'Female',
                'place_of_birth'=> !empty($row['tempat_lahir']) ? trim($row['tempat_lahir']) : null,
                'date_of_birth' => $tanggalLahir,
                'classroom_id'  => $classroom?->id,
                'archive_id'    => $activeArchive?->id,
                'password'      => bcrypt($nisn),
            ]);

            $this->importedCount++;
        }
    }
}