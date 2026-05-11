<?php

namespace App\Filament\Resources\SlugResource\Pages;

use App\Filament\Resources\SlugResource;
use App\Models\Slugs;
use App\Models\Schedule;
use App\Models\Classroom;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSlugs extends ListRecords
{
    protected static string $resource = SlugResource::class;

    public ?int $selectedClassroomId = null;
    public ?int $selectedScheduleId = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat'),
        ];
    }

    public function selectClassroom(int $classroomId): void
    {
        $this->selectedClassroomId = $classroomId;
        $this->selectedScheduleId = null;
    }

    public function selectSchedule(int $scheduleId): void
    {
        $this->selectedScheduleId = $scheduleId;
    }

    public function back(): void
    {
        if ($this->selectedScheduleId) {
            $this->selectedScheduleId = null;
        } elseif ($this->selectedClassroomId) {
            $this->selectedClassroomId = null;
        }
    }

    public function getClassrooms()
    {
        return Classroom::whereHas('schedules', function ($q) {
            $q->whereHas('slugs');
        })->orderBy('name')->get();
    }

    public function getSchedules()
    {
        return Schedule::with('subject')
            ->where('classroom_id', $this->selectedClassroomId)
            ->whereHas('slugs')
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
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.slug-resource.pages.list-slugs';
    }
}
