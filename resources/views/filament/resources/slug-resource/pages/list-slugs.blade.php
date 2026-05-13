<x-filament-panels::page>
<div style="font-family: inherit;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#9ca3af;flex-wrap:wrap;margin-bottom:24px;">
        <button wire:click="back"
            style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
            onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
            Manajemen Tugas &amp; Materi
        </button>

        @if ($selectedClassroom)
            <span style="color:#d1d5db;">/</span>
            <button wire:click="back"
                style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ $selectedClassroom->name }}
            </button>
        @endif

        @if ($selectedSchedule)
            <span style="color:#d1d5db;">/</span>
            <span style="font-weight:700;color:#1f2937;">{{ $selectedSchedule->subject->name }}</span>
        @endif
    </div>

    {{-- STEP 1: PILIH KELAS --}}
    @if (!$selectedClassroomId)
        <p style="font-size:13px;font-weight:700;color:#6b7280;margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em;">Kelas</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            @forelse ($classrooms as $classroom)
                <button wire:click="selectClassroom({{ $classroom->id }})"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <div>
                            <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $classroom->name }}</p>
                            <p style="font-size:13px;color:#9ca3af;margin:3px 0 0;">Lihat mata pelajaran</p>
                        </div>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <p style="color:#6b7280;font-weight:500;">Belum ada kelas yang diampu.</p>
                </div>
            @endforelse
        </div>

    {{-- STEP 2: PILIH MAPEL --}}
    @elseif (!$selectedScheduleId)
        <p style="font-size:13px;font-weight:700;color:#6b7280;margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em;">Mata Pelajaran</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            @forelse ($schedules as $schedule)
                <button wire:click="selectSchedule({{ $schedule->id }})"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $schedule->subject->name }}</p>
                            <p style="font-size:13px;color:#9ca3af;margin:3px 0 0;">Kelas {{ $selectedClassroom->name }}</p>
                        </div>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <p style="color:#6b7280;font-weight:500;">Belum ada mata pelajaran.</p>
                </div>
            @endforelse
        </div>

    {{-- STEP 3: DAFTAR BAB --}}
    @else
        {{-- Header bab + tombol tambah --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <p style="font-size:13px;font-weight:600;color:#6b7280;margin:0;">
                <span style="color:#1f2937;">{{ $selectedSchedule->subject->name }}</span>
                <span style="color:#d1d5db;margin:0 6px;">–</span>
                <span style="color:#1f2937;">{{ $selectedClassroom->name }}</span>
            </p>
            <button wire:click="openCreateModal"
                style="display:flex;align-items:center;gap:8px;background:#2563eb;color:#fff;border:none;border-radius:10px;padding:9px 18px;font-size:14px;font-weight:600;cursor:pointer;"
                onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Bab
            </button>
        </div>

        {{-- Kartu bab --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
            @forelse ($slugs as $slug)
                <a href="{{ route('filament.m.resources.slugs.edit', $slug) }}"
                    style="display:block;text-decoration:none;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;"
                    onmouseover="this.style.borderColor='#c4b5fd';this.style.boxShadow='0 2px 8px #ede9fe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                            </svg>
                        </div>
                        <p style="font-weight:600;color:#1f2937;font-size:14px;margin:0;">{{ $slug->title }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <p style="color:#6b7280;font-weight:500;">Belum ada bab. Klik "Tambah Bab" untuk mulai.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- MODAL TAMBAH BAB --}}
    @if ($showCreateModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:50;display:flex;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:16px;padding:28px;width:100%;max-width:440px;box-shadow:0 8px 32px rgba(0,0,0,0.15);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-size:16px;font-weight:700;color:#1f2937;margin:0;">Tambah Bab Baru</h3>
                    <button wire:click="closeCreateModal"
                        style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:20px;line-height:1;"
                        onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">✕</button>
                </div>

                <p style="font-size:13px;color:#6b7280;margin:0 0 16px;">
                    {{ $selectedSchedule->subject->name }} – {{ $selectedClassroom->name }}
                </p>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Judul Bab</label>
                    <input
                        wire:model="newTitle"
                        type="text"
                        placeholder="Contoh: Bab 1 - Pengenalan"
                        style="width:100%;border:1.5px solid #d1d5db;border-radius:8px;padding:10px 12px;font-size:14px;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'"
                        wire:keydown.enter="createSlug"
                    />
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button wire:click="closeCreateModal"
                        style="padding:9px 18px;border:1.5px solid #e5e7eb;border-radius:8px;background:#fff;color:#6b7280;font-size:14px;font-weight:600;cursor:pointer;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                        Batal
                    </button>
                    <button wire:click="createSlug"
                        style="padding:9px 18px;border:none;border-radius:8px;background:#2563eb;color:#fff;font-size:14px;font-weight:600;cursor:pointer;"
                        onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        Simpan & Lanjut
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
</x-filament-panels::page>