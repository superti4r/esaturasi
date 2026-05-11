<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Archive;
use App\Models\Schedule;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ScheduleResource;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!Archive::where('status', 'Active')->exists()) {
            Notification::make()
                ->title('Tidak ada arsip aktif')
                ->body('Silakan tambahkan arsip terlebih dahulu sebelum mengubah jadwal.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(ScheduleResource::getUrl());
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->validateJadwalBentrok($data['schedule'], $this->record->id);
        return $data;
    }

    protected function validateJadwalBentrok(array $jadwalBaru, ?int $excludeId): void
    {
        $allSchedules = Schedule::when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->get();

        foreach ($jadwalBaru as $baru) {
            $hariB    = $baru['day'];
            $mulaiB   = strtotime($baru['start']);
            $selesaiB = strtotime($baru['end']);

            foreach ($allSchedules as $schedule) {
                $existingList = $schedule->schedule ?? [];

                foreach ($existingList as $existing) {
                    if ($existing['day'] !== $hariB) continue;

                    $mulaiE   = strtotime($existing['start']);
                    $selesaiE = strtotime($existing['end']);

                    $bentrok = $mulaiB < $selesaiE && $selesaiB > $mulaiE;

                    if ($bentrok) {
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