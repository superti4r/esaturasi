<x-filament-panels::page>

    {{-- Tab Buttons --}}
    <div class="flex gap-2 mb-4">
        <button
            wire:click="setTab('pretest')"
            @class([
                'px-4 py-2 rounded-lg font-semibold text-sm transition',
                'bg-primary-600 text-white shadow' => $activeTab === 'pretest',
                'bg-white text-gray-600 border hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' => $activeTab !== 'pretest',
            ])
        >
            Pretest
        </button>

        <button
            wire:click="setTab('posttest')"
            @class([
                'px-4 py-2 rounded-lg font-semibold text-sm transition',
                'bg-primary-600 text-white shadow' => $activeTab === 'posttest',
                'bg-white text-gray-600 border hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' => $activeTab !== 'posttest',
            ])
        >
            Posttest
        </button>
    </div>

    {{-- Judul --}}
    <div class="mb-2 text-lg font-bold text-gray-700 dark:text-gray-200">
        {{ $activeTab === 'pretest' ? 'Hasil Pretest Siswa' : 'Hasil Posttest Siswa' }}
    </div>

    {{-- Tabel --}}
    {{ $this->table }}

</x-filament-panels::page>