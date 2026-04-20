<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Jadwal Hari Ini — {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </x-slot>

        @php $jadwal = $this->getJadwal(); @endphp

        @if(empty($jadwal))
            <p class="text-sm text-gray-400 text-center py-6">Tidak ada jadwal mengajar hari ini.</p>
        @else
            <div class="flex flex-col gap-3">
                @foreach($jadwal as $item)
                    <div @class([
                        'flex items-center gap-4 p-4 rounded-xl border',
                        'opacity-50 border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-700' => $item['status'] === 'selesai',
                        'border-2 border-green-500 bg-white dark:bg-gray-900' => $item['status'] === 'berlangsung',
                        'border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-700' => $item['status'] === 'mendatang',
                    ])>
                        {{-- Jam --}}
                        <div class="text-center min-w-[56px]">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-100">{{ $item['start'] }}</div>
                            <div class="text-xs text-gray-400">{{ $item['end'] }}</div>
                        </div>

                        <div class="w-px h-9 bg-gray-200 dark:bg-gray-700"></div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $item['mapel'] }}</p>
                            <p class="text-xs text-gray-500">{{ $item['kelas'] }}</p>
                        </div>

                        {{-- Badge --}}
                        @if($item['status'] === 'selesai')
                            <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800">Selesai</span>
                        @elseif($item['status'] === 'berlangsung')
                            <span class="text-xs font-medium px-3 py-1 rounded-full bg-green-100 text-green-700 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Berlangsung
                            </span>
                        @else
                            <span class="text-xs font-medium px-3 py-1 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30">Mendatang</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>