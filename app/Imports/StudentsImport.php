<?php

namespace App\Imports;

use App\Models\Archive;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToCollection, WithHeadingRow, WithStartRow, SkipsEmptyRows
{
    public int $importedCount = 0;
    public int $skippedCount = 0;
    private ?int $activeArchiveId = null;

    public function __construct()
    {
        $this->activeArchiveId = Archive::where('status', 'Active')->value('id');
    }

    private array $majorMap = [
        'RPL'  => 1,
        'TITL' => 2,
        'TKR'  => 3,
        'MM'   => 4,
        'DKV'  => 4,
    ];

    public function headingRow(): int
    {
        return 1;
    }

    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nisn = trim((string) ($row['nisn'] ?? ''));
            $nama = trim((string) ($row['nama'] ?? ''));

            if (empty($nisn) || empty($nama) || !is_numeric($nisn)) {
                $this->skippedCount++;
                continue;
            }

            $nisn = str_pad($nisn, 10, '0', STR_PAD_LEFT);

            if (Student::where('nisn', $nisn)->where('archive_id', $this->activeArchiveId)->exists()) {
                $this->skippedCount++;
                continue;
            }

            $classroom = null;
            if (!empty($row['rombel_saat_ini'])) {
                $rombelName = trim($row['rombel_saat_ini']);
                $classroom = Classroom::where('name', $rombelName)->first();

                if (!$classroom) {
                    $majorId = $this->getMajorId($rombelName);
                    $classroom = Classroom::create([
                        'name'     => $rombelName,
                        'major_id' => $majorId,
                    ]);
                }
            }

            Student::create([
                'nisn'           => $nisn,
                'nipd'           => !empty($row['nipd']) ? trim($row['nipd']) : null,
                'name'           => ucwords(strtolower($nama)),
                'place_of_birth' => !empty($row['tempat_lahir'])
                                        ? ucwords(strtolower(trim($row['tempat_lahir'])))
                                        : null,
                'date_of_birth'  => $this->parseDate($row['tanggal_lahir'] ?? null),
                'gender'         => isset($row['jk']) && strtoupper(trim($row['jk'])) === 'L'
                                        ? 'Male'
                                        : 'Female',
                'classroom_id'   => $classroom?->id,
                'archive_id'     => $this->activeArchiveId,
                'address'        => null,
                'email'          => null,
                'password'       => bcrypt($nisn),
                'avatar_url'     => null,
            ]);

            $this->importedCount++;
        }
    }

    private function getMajorId(string $rombelName): int
    {
        foreach ($this->majorMap as $keyword => $majorId) {
            if (str_contains(strtoupper($rombelName), $keyword)) {
                return $majorId;
            }
        }
        return 1;
    }

    private function parseDate($value): ?string
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                    ->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}