<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Major;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MajorClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $basePath = base_path();
        $studentExcel = $basePath . DIRECTORY_SEPARATOR . 'database/seeders/xlsx/siswa.xlsx';

        if (!file_exists($studentExcel)) {
            $this->command?->warn("File not found: {$studentExcel}. Skipping Major/Classroom seeding.");
            return;
        }

        $spreadsheet = IOFactory::load($studentExcel);
        $sheet = $spreadsheet->getSheet(0);

        $highestRow = (int) $sheet->getHighestDataRow();

        // Header di baris 1, baris 2 kosong, data mulai baris 3.
        for ($row = 3; $row <= $highestRow; $row++) {
            $rombel = trim((string) $sheet->getCell('H' . $row)->getFormattedValue());
            if ($rombel === '') {
                continue;
            }

            $rombelUpper = strtoupper(trim($rombel));
            $tokens = preg_split('/\s+/', $rombelUpper) ?: [];
            $majorCode = null;
            foreach ($tokens as $token) {
                if (preg_match('/^[A-Z]{2,5}$/', $token)) {
                    $majorCode = $token;
                    break;
                }
            }
            $majorCode = $majorCode ?: 'UNKNOWN';

            $major = Major::firstOrCreate(
                ['major_code' => $majorCode],
                ['name' => $majorCode]
            );

            Classroom::firstOrCreate(
                ['name' => $rombel],
                ['major_id' => $major->id]
            );
        }
    }
}
