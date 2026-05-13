<?php

namespace App\Filament\Resources\SlugResource\Pages;

use App\Filament\Resources\SlugResource;
use App\Models\Slugs;
use App\Models\Schedule;
use App\Models\Classroom;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListSlugs extends ListRecords
{
    protected static string $resource = SlugResource::class;

    public ?int $selectedClassroomId = null;
    public ?int $selectedScheduleId = null;
    public bool $showCreateModal = false;
    public string $newTitle = '';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function selectClassroom(int $classroomId): void
    {
        $this->selectedClassroomId = $classroomId;
        $this->selectedScheduleId = null;
    }

    public function selectSchedule(int $scheduleId): void
    {
        $this->selectedScheduleId = $scheduleId;
        $this->showCreateModal = false;
        $this->newTitle = '';
    }

    public function back(): void
    {
        if ($this->selectedScheduleId) {
            $this->selectedScheduleId = null;
        } elseif ($this->selectedClassroomId) {
            $this->selectedClassroomId = null;
        }
    }

    public function openCreateModal(): void
    {
        $this->newTitle = '';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->newTitle = '';
    }

    public function createSlug(): void
    {
        if (empty(trim($this->newTitle))) {
            Notification::make()
                ->title('Judul tidak boleh kosong!')
                ->danger()
                ->send();
            return;
        }

        $slug = Slugs::create([
            'schedule_id' => $this->selectedScheduleId,
            'title'       => trim($this->newTitle),
        ]);

        $this->showCreateModal = false;
        $this->newTitle = '';

        Notification::make()
            ->title('Bab berhasil ditambahkan!')
            ->success()
            ->send();

        // Redirect ke halaman edit bab yang baru dibuat
        $this->redirect(
            route('filament.m.resources.slugs.edit', $slug),
            navigate: false
        );
    }

    public function getClassrooms()
    {
        $guruId = auth()->id();

        return Classroom::whereHas('schedules', function ($q) use ($guruId) {
            $q->where('teacher_id', $guruId)
              ->whereHas('archive', fn($q) => $q->where('status', 'Active'));
        })->orderBy('name')->get();
    }

    public function getSchedules()
    {
        $guruId = auth()->id();

        return Schedule::with('subject')
            ->where('classroom_id', $this->selectedClassroomId)
            ->where('teacher_id', $guruId)
            ->whereHas('archive', fn($q) => $q->where('status', 'Active'))
            ->get();
    }

    public function getSlugs()
    {
        return Slugs::where('schedule_id', $this->selectedScheduleId)->get();
    }

    protected function getViewData(): array
    {
        return [
            'classrooms'          => $this->getClassrooms(),
            'schedules'           => $this->selectedClassroomId ? $this->getSchedules() : collect(),
            'slugs'               => $this->selectedScheduleId ? $this->getSlugs() : collect(),
            'selectedClassroomId' => $this->selectedClassroomId,
            'selectedScheduleId'  => $this->selectedScheduleId,
            'selectedClassroom'   => $this->selectedClassroomId ? Classroom::find($this->selectedClassroomId) : null,
            'selectedSchedule'    => $this->selectedScheduleId ? Schedule::with('subject')->find($this->selectedScheduleId) : null,
            'showCreateModal'     => $this->showCreateModal,
            'newTitle'            => $this->newTitle,
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.slug-resource.pages.list-slugs';
    }
}