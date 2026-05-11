<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Archive;
use App\Models\Schedule;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    // ✅ Hilangkan breadcrumb otomatis Filament (penyebab double)
    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum membuat jadwal.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(ScheduleResource::getUrl());
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['schedule'] as $item) {
            if ($item['start'] < '07:00' || $item['end'] > '16:00') {
                Notification::make()
                    ->title('Jam tidak valid!')
                    ->body('Jam jadwal harus antara 07:00 - 16:00.')
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();
            }

            if ($item['start'] >= $item['end']) {
                Notification::make()
                    ->title('Jam tidak valid!')
                    ->body('Jam mulai harus lebih awal dari jam selesai.')
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();
            }
        }

        // ✅ Kirim teacher_id dari form data agar filter guru benar
        $this->validateJadwalBentrok($data['schedule'], null, $data['teacher_id'] ?? null);
        return $data;
    }

    protected function validateJadwalBentrok(array $jadwalBaru, ?int $excludeId, ?int $guruId = null): void
    {
        // ✅ Filter hanya guru yang sama agar tidak false alarm antar guru berbeda
        $allSchedules = Schedule::when($guruId, fn($q) => $q->where('teacher_id', $guruId))
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->get();

        foreach ($jadwalBaru as $baru) {
            $hariB    = $baru['day'];
            $mulaiB   = strtotime($baru['start'] ?? '00:00');
            $selesaiB = strtotime($baru['end']   ?? '00:00');

            foreach ($allSchedules as $schedule) {
                foreach ($schedule->schedule ?? [] as $existing) {
                    if ($existing['day'] !== $hariB) continue;

                    $mulaiE   = strtotime($existing['start'] ?? '00:00');
                    $selesaiE = strtotime($existing['end']   ?? '00:00');

                    if ($mulaiB < $selesaiE && $selesaiB > $mulaiE) {
                        Notification::make()
                            ->title('Jadwal Bentrok!')
                            ->body("Guru sudah memiliki jadwal di hari {$hariB} jam {$existing['start']} - {$existing['end']}.")
                            ->danger()
                            ->persistent()
                            ->send();

                        $this->halt();
                    }
                }
            }
        }
    }
}