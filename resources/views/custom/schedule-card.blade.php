<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($groupedSchedules as $className => $schedules)
            <x-filament::card class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-md">
                <h2 class="text-lg font-semibold text-primary dark:text-primary-400 mb-4">
                    {{ $className }}
                </h2>

                @php
                    $groupedBySubjectAndTeacher = collect($schedules)->groupBy(fn ($schedule) => $schedule->subject_id . '-' . $schedule->teacher_id);
                @endphp

                <div class="space-y-4">
                    @foreach ($groupedBySubjectAndTeacher as $group)
                        @php
                            $first = $group->first();
                            $items = [];

                            foreach ($group as $schedule) {
                                foreach ($schedule->schedule as $item) {
                                    $items[] = $item;
                                }
                            }

                            $groupedByDay = collect($items)->groupBy('day');
                            $isOwnSchedule = auth()->id() === $first->teacher_id;
                        @endphp

                        <div class="p-4 rounded-md bg-gray-50 dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <div class="font-semibold text-base text-primary dark:text-primary-400">
                                        {{ $first->subject->name }}
                                    </div>
                                    <div class="text-sm">
                                        {{ $first->teacher->name }}
                                    </div>
                                </div>

                                @if ($isOwnSchedule)
                                    <x-filament::badge color="success">
                                        Jadwal Kamu
                                    </x-filament::badge>
                                @endif
                            </div>

                            <hr class="border-gray-300 dark:border-gray-700 my-2">
                            
                            <div class="space-y-1">
                                @foreach ($groupedByDay as $day => $dayItems)
                                    @foreach ($dayItems as $item)
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-o-calendar class="w-4 h-4 text-primary" />
                                            <span>{{ ucfirst($day) }} ({{ $item['start'] }} - {{ $item['end'] }})</span>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>

                            <div class="flex justify-end mt-3">
                                <a href="{{ $recordPageUrl($first) }}"
                                    class="text-xs text-blue-600 hover:underline flex items-center gap-1 font-bold">
                                    <x-heroicon-o-pencil class="w-4 h-4 text-blue-600" />
                                    Kelola
                                </a>
                            </div>
                            
                        </div>
                    @endforeach
                </div>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::page>
