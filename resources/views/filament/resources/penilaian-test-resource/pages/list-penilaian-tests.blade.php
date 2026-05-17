<x-filament-panels::page>
<div style="font-family: inherit;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#9ca3af;flex-wrap:wrap;margin-bottom:24px;">
        <button wire:click="backToKelas"
            style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
            onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
            Penilaian
        </button>

        @if ($level !== 'kelas')
            <span style="color:#d1d5db;">/</span>
            <button wire:click="backToMapel"
                style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ $selectedKelasLabel }}
            </button>
        @endif

        @if (in_array($level, ['tab', 'slug', 'detail', 'siswa']))
            <span style="color:#d1d5db;">/</span>
            <button wire:click="backToTab"
                style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ $selectedMapelLabel }}
            </button>
        @endif

        @if (in_array($level, ['slug', 'detail', 'siswa']))
            <span style="color:#d1d5db;">/</span>
            <button wire:click="backToSlug"
                style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ ucfirst($activeTab) }}
            </button>
        @endif

        @if (in_array($level, ['detail', 'siswa']))
            <span style="color:#d1d5db;">/</span>
            @if ($selectedSlugLabel)
                <button wire:click="backToSlug"
                    style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                    onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                    {{ $selectedSlugLabel }}
                </button>
                <span style="color:#d1d5db;">/</span>
            @endif
            <button wire:click="backToDetail"
                style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ $selectedLabel }}
            </button>
        @endif

        @if ($level === 'siswa')
            <span style="color:#d1d5db;">/</span>
            <span style="font-weight:700;color:#1f2937;">{{ $selectedSiswaLabel }}</span>
        @endif
    </div>

    {{-- LEVEL 0: PILIH KELAS --}}
    @if ($level === 'kelas')
        <p style="font-size:13px;font-weight:700;color:#6b7280;margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em;">Kelas</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            @forelse ($this->getKelasData() as $item)
                <button wire:click="selectKelas({{ $item['id'] }}, '{{ addslashes($item['label']) }}')"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <x-heroicon-o-academic-cap style="width:22px;height:22px;color:#2563eb;" />
                        </div>
                        <div>
                            <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $item['label'] }}</p>
                            <p style="font-size:13px;color:#9ca3af;margin:3px 0 0;">Lihat mata pelajaran</p>
                        </div>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <p style="color:#6b7280;font-weight:500;">Tidak ada kelas yang diajarkan.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- LEVEL 1: PILIH MAPEL --}}
    @if ($level === 'mapel')
        <p style="font-size:13px;font-weight:700;color:#6b7280;margin:0 0 16px;text-transform:uppercase;letter-spacing:.05em;">Mata Pelajaran</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            @forelse ($this->getMapelData() as $item)
                <button wire:click="selectMapel({{ $item['schedule_id'] }}, '{{ addslashes($item['mapel']) }}')"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <x-heroicon-o-book-open style="width:22px;height:22px;color:#2563eb;" />
                        </div>
                        <div>
                            <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $item['mapel'] }}</p>
                            <p style="font-size:13px;color:#9ca3af;margin:3px 0 0;">Kelas {{ $item['kelas'] }}</p>
                        </div>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <p style="color:#6b7280;font-weight:500;">Tidak ada mata pelajaran di kelas ini.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- LEVEL 2+: TAB NAVIGATION + KONTEN --}}
    @if (in_array($level, ['tab', 'slug', 'detail', 'siswa']))

        {{-- TAB NAVIGATION --}}
        <div style="display:flex;gap:4px;background:#f3f4f6;border-radius:12px;padding:4px;margin-bottom:24px;width:fit-content;">
            @foreach (['pretest' => 'Pretest', 'tugas' => 'Tugas', 'posttest' => 'Posttest'] as $tabKey => $tabLabel)
                <button
                   wire:click="setTab('{{ $tabKey }}')"
                    style="padding:8px 20px;border-radius:9px;font-size:13px;font-weight:600;border:none;cursor:pointer;transition:all .15s;
                        background:{{ $activeTab === $tabKey ? '#fff' : 'transparent' }};
                        color:{{ $activeTab === $tabKey ? '#1f2937' : '#9ca3af' }};
                        box-shadow:{{ $activeTab === $tabKey ? '0 1px 4px rgba(0,0,0,.08)' : 'none' }};"
                    onmouseover="if('{{ $activeTab }}' !== '{{ $tabKey }}') this.style.color='#6b7280'"
                    onmouseout="if('{{ $activeTab }}' !== '{{ $tabKey }}') this.style.color='#9ca3af'"
                >
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>

        {{-- LEVEL 2: PILIH SLUG/BAB --}}
        @if ($level === 'tab')
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
                @forelse ($this->getSlugData() as $slug)
                    <button
                        wire:click="selectSlug({{ $slug['id'] }}, '{{ addslashes($slug['judul']) }}')"
                        style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;cursor:pointer;width:100%;"
                        onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                        onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                    >
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <x-heroicon-o-folder-open style="width:20px;height:20px;color:#7c3aed;" />
                            </div>
                            <p style="font-weight:600;color:#1f2937;font-size:14px;margin:0;">{{ $slug['judul'] }}</p>
                        </div>
                    </button>
                @empty
                    <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                        <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                        <p style="color:#6b7280;font-weight:500;">Tidak ada bab dengan {{ ucfirst($activeTab) }}.</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- LEVEL 3: DAFTAR ITEM DALAM SLUG --}}
        @if ($level === 'slug')
            @php $items = $this->getListData(); @endphp
            <div style="display:flex;flex-direction:column;gap:12px;">
                @forelse ($items as $item)
                    <button
                        wire:click="selectItem({{ $item['id'] }}, '{{ addslashes($item['judul']) }}')"
                        style="display:flex;align-items:center;justify-content:space-between;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:16px 20px;text-align:left;cursor:pointer;width:100%;"
                        onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                        onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                    >
                        <div style="display:flex;align-items:center;gap:16px;">
                            <div style="width:42px;height:42px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                                background:{{ $activeTab === 'pretest' ? '#dbeafe' : ($activeTab === 'tugas' ? '#fef3c7' : '#dcfce7') }};
                                color:{{ $activeTab === 'pretest' ? '#2563eb' : ($activeTab === 'tugas' ? '#d97706' : '#16a34a') }};">
                                @if ($activeTab === 'pretest')
                                    <x-heroicon-o-document-text style="width:20px;height:20px;" />
                                @elseif ($activeTab === 'tugas')
                                    <x-heroicon-o-clipboard-document-list style="width:20px;height:20px;" />
                                @else
                                    <x-heroicon-o-check-badge style="width:20px;height:20px;" />
                                @endif
                            </div>
                            <div>
                                <p style="font-weight:600;color:#1f2937;font-size:15px;margin:0;">{{ $item['judul'] }}</p>
                                <p style="font-size:13px;color:#9ca3af;margin:4px 0 0;">{{ $item['jumlah'] }} siswa mengumpulkan</p>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;margin-left:16px;">
                            <span style="font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;
                                background:{{ $item['jumlah'] > 0 ? '#dcfce7' : '#f3f4f6' }};
                                color:{{ $item['jumlah'] > 0 ? '#16a34a' : '#9ca3af' }};">
                                {{ $item['jumlah'] }} siswa
                            </span>
                            <x-heroicon-o-chevron-right style="width:18px;height:18px;color:#d1d5db;" />
                        </div>
                    </button>
                @empty
                    <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                        <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                        <p style="color:#6b7280;font-weight:500;">Belum ada data {{ ucfirst($activeTab) }} di bab ini.</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- LEVEL 4: TABEL NILAI SISWA --}}
        @if ($level === 'detail')
            @php $rows = $this->getDetailData(); @endphp

            @if ($rows->count() > 0)
                @php
                    $stats = [
                        ['label' => 'Total Siswa', 'value' => $rows->count(),             'bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'heroicon-o-users'],
                        ['label' => 'Rata-rata',   'value' => round($rows->avg('nilai')), 'bg' => '#ede9fe', 'color' => '#7c3aed', 'icon' => 'heroicon-o-calculator'],
                    ];
                    if ($activeTab !== 'tugas') {
                        $stats[] = ['label' => 'Lulus',       'value' => $rows->where('status', true)->count(),  'bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'heroicon-o-check-circle'];
                        $stats[] = ['label' => 'Tidak Lulus', 'value' => $rows->where('status', false)->count(), 'bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'heroicon-o-x-circle'];
                    } else {
                        $stats[] = ['label' => 'Tertinggi', 'value' => $rows->max('nilai') ?? '-', 'bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'heroicon-o-arrow-trending-up'];
                        $stats[] = ['label' => 'Terendah',  'value' => $rows->min('nilai') ?? '-', 'bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'heroicon-o-arrow-trending-down'];
                    }
                @endphp
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
                    @foreach ($stats as $s)
                        <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                            <div style="width:42px;height:42px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:{{ $s['bg'] }};color:{{ $s['color'] }};">
                                <x-dynamic-component :component="$s['icon']" style="width:20px;height:20px;" />
                            </div>
                            <div>
                                <p style="font-size:22px;font-weight:800;color:#1f2937;margin:0;line-height:1.1;">{{ $s['value'] }}</p>
                                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">{{ $s['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;font-size:14px;">
                    <thead>
                        <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:40px;">#</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;">Nama Siswa</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:140px;">Kelas</th>
                            <th style="padding:12px 20px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:90px;">Nilai</th>
                            @if ($activeTab === 'tugas')
                                <th style="padding:12px 20px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:130px;">Status Nilai</th>
                            @else
                                <th style="padding:12px 20px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:130px;">Status</th>
                            @endif
                            <th style="padding:12px 20px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;width:100px;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $i => $row)
                            @php
                                $n  = $row['nilai'] ?? 0;
                                $bg = $n >= 80 ? '#dcfce7' : ($n >= 60 ? '#fef3c7' : '#fee2e2');
                                $fc = $n >= 80 ? '#16a34a' : ($n >= 60 ? '#d97706' : '#dc2626');
                            @endphp
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td style="padding:14px 20px;color:#d1d5db;">{{ $i + 1 }}</td>
                                <td style="padding:14px 20px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <span style="font-size:12px;font-weight:700;color:#2563eb;">{{ strtoupper(substr($row['siswa'], 0, 1)) }}</span>
                                        </div>
                                        <span style="font-weight:600;color:#1f2937;">{{ $row['siswa'] }}</span>
                                    </div>
                                </td>
                                <td style="padding:14px 20px;color:#6b7280;">{{ $row['kelas'] }}</td>
                                <td style="padding:14px 20px;text-align:center;">
                                    @if ($activeTab === 'tugas' && ($row['nilai'] === null || $row['nilai'] === ''))
                                        <span style="font-size:12px;color:#9ca3af;font-style:italic;">Belum dinilai</span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:30px;border-radius:8px;font-weight:700;font-size:13px;background:{{ $bg }};color:{{ $fc }};">
                                            {{ $n }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding:14px 20px;text-align:center;">
                                    @if ($activeTab === 'tugas')
                                        @if ($row['status'] === 'graded')
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#dcfce7;color:#16a34a;">
                                                <x-heroicon-o-check-circle style="width:12px;height:12px;" />
                                                Sudah Dinilai
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#fef3c7;color:#d97706;">
                                                <x-heroicon-o-clock style="width:12px;height:12px;" />
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    @else
                                        @if ($row['status'])
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#dcfce7;color:#16a34a;">
                                                <x-heroicon-o-check-circle style="width:12px;height:12px;" />
                                                Lulus
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#fee2e2;color:#dc2626;">
                                                <x-heroicon-o-x-circle style="width:12px;height:12px;" />
                                                Tidak Lulus
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td style="padding:14px 20px;text-align:center;">
                                    <button
                                        wire:click="selectSiswa({{ $row['student_id'] }}, '{{ addslashes($row['siswa']) }}')"
                                        style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;background:#2563eb;color:#fff;border:none;cursor:pointer;"
                                        onmouseover="this.style.background='#1d4ed8'"
                                        onmouseout="this.style.background='#2563eb'"
                                    >
                                        <x-heroicon-o-eye style="width:14px;height:14px;" />
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding:60px 20px;text-align:center;color:#9ca3af;">
                                    Belum ada siswa yang mengumpulkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- LEVEL 5: DETAIL PER SISWA --}}
        @if ($level === 'siswa')
            @php $data = $this->getSiswaData(); @endphp

            {{-- TUGAS --}}
            @if ($activeTab === 'tugas')
                @if ($data && $data['file_path'])
                    @php
                        $ext     = $data['file_ext'];
                        $fileUrl = $data['file_url'];
                        $isPdf   = $ext === 'pdf';
                        $isImg   = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

                        {{-- Preview file --}}
                        <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <x-heroicon-o-paper-clip style="width:18px;height:18px;color:#6b7280;" />
                                    <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $data['file_name'] }}</span>
                                </div>
                                <a href="{{ $fileUrl }}" target="_blank"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;background:#f3f4f6;color:#374151;text-decoration:none;">
                                    <x-heroicon-o-arrow-down-tray style="width:14px;height:14px;" />
                                    Download
                                </a>
                            </div>
                            <div style="padding:20px;min-height:300px;display:flex;align-items:center;justify-content:center;background:#f9fafb;">
                                @if ($isPdf)
                                    <iframe src="{{ $fileUrl }}" style="width:100%;height:600px;border:none;border-radius:8px;"></iframe>
                                @elseif ($isImg)
                                    <img src="{{ $fileUrl }}" style="max-width:100%;max-height:600px;border-radius:8px;object-fit:contain;" />
                                @else
                                    <div style="text-align:center;">
                                        <x-heroicon-o-document style="width:48px;height:48px;color:#d1d5db;margin:0 auto 12px;" />
                                        <p style="color:#6b7280;margin:0 0 16px;">Tidak dapat menampilkan preview untuk file ini.</p>
                                        <a href="{{ $fileUrl }}" target="_blank"
                                            style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;font-size:14px;font-weight:600;background:#2563eb;color:#fff;text-decoration:none;">
                                            <x-heroicon-o-arrow-down-tray style="width:16px;height:16px;" />
                                            Buka File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Panel kanan: info + form nilai --}}
                        <div style="display:flex;flex-direction:column;gap:14px;">

                            {{-- Waktu Pengumpulan --}}
                            <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;">
                                <p style="font-size:11px;color:#9ca3af;margin:0 0 12px;text-transform:uppercase;font-weight:700;letter-spacing:.05em;">Waktu Pengumpulan</p>

                                @if ($data['submitted_at'])
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                        <x-heroicon-o-clock style="width:15px;height:15px;color:#6b7280;flex-shrink:0;" />
                                        <span style="font-size:13px;font-weight:600;color:#1f2937;">{{ $data['submitted_at'] }}</span>
                                    </div>
                                @endif

                                @if ($data['deadline'])
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                        <x-heroicon-o-calendar-days style="width:15px;height:15px;color:#6b7280;flex-shrink:0;" />
                                        <span style="font-size:12px;color:#9ca3af;">Deadline: {{ $data['deadline'] }}</span>
                                    </div>
                                @endif

                                @if ($data['is_telat'])
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#fee2e2;color:#dc2626;">
                                        <x-heroicon-o-exclamation-triangle style="width:13px;height:13px;" />
                                        Terlambat
                                    </span>
                                @elseif ($data['submitted_at'])
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:600;background:#dcfce7;color:#16a34a;">
                                        <x-heroicon-o-check-circle style="width:13px;height:13px;" />
                                        Tepat Waktu
                                    </span>
                                @endif
                            </div>

                            {{-- Form Nilai --}}
                            <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;">
                                <p style="font-size:11px;color:#9ca3af;margin:0 0 12px;text-transform:uppercase;font-weight:700;letter-spacing:.05em;">Beri Nilai</p>

                                @if ($nilaiSaved)
                                    <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:#dcfce7;border:1.5px solid #86efac;margin-bottom:12px;">
                                        <x-heroicon-o-check-circle style="width:16px;height:16px;color:#16a34a;flex-shrink:0;" />
                                        <span style="font-size:13px;font-weight:600;color:#16a34a;">Nilai berhasil disimpan!</span>
                                    </div>
                                @endif

                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="flex:1;">
                                        <input
                                            type="number"
                                            wire:model="editNilai"
                                            min="0"
                                            max="100"
                                            placeholder="0 - 100"
                                            style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:15px;font-weight:600;color:#1f2937;outline:none;box-sizing:border-box;"
                                            onfocus="this.style.borderColor='#2563eb'"
                                            onblur="this.style.borderColor='#e5e7eb'"
                                        />
                                        @error('editNilai')
                                            <p style="color:#dc2626;font-size:11px;margin:4px 0 0;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <button
                                    wire:click="saveNilai"
                                    wire:loading.attr="disabled"
                                    style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:14px;font-weight:600;background:#2563eb;color:#fff;border:none;cursor:pointer;"
                                    onmouseover="this.style.background='#1d4ed8'"
                                    onmouseout="this.style.background='#2563eb'"
                                >
                                    <span wire:loading.remove wire:target="saveNilai">
                                        <x-heroicon-o-check style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:4px;" />
                                        Simpan Nilai
                                    </span>
                                    <span wire:loading wire:target="saveNilai" style="font-size:13px;">
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>

                            {{-- Preview nilai saat ini --}}
                            @if ($editNilai !== null && $editNilai !== '')
                                @php
                                    $pv   = (int) $editNilai;
                                    $pvBg = $pv >= 80 ? '#dcfce7' : ($pv >= 60 ? '#fef3c7' : '#fee2e2');
                                    $pvFc = $pv >= 80 ? '#16a34a' : ($pv >= 60 ? '#d97706' : '#dc2626');
                                @endphp
                                <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;text-align:center;">
                                    <p style="font-size:11px;color:#9ca3af;margin:0 0 8px;text-transform:uppercase;font-weight:700;letter-spacing:.05em;">Preview Nilai</p>
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:48px;border-radius:12px;font-weight:800;font-size:24px;background:{{ $pvBg }};color:{{ $pvFc }};">
                                        {{ $pv }}
                                    </span>
                                </div>
                            @endif

                        </div>
                    </div>
                @else
                    <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                        <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                        <p style="color:#6b7280;font-weight:500;">Siswa belum mengumpulkan tugas.</p>
                    </div>
                @endif

            {{-- PRETEST / POSTTEST --}}
            @else
                @if (!empty($data['soal']) && $data['soal']->count() > 0)
                    @php
                        $benar      = $data['soal']->where('is_benar', true)->count();
                        $salah      = $data['soal']->where('is_benar', false)->count();
                        $nilaiSiswa = $data['nilai'];
                    @endphp
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
                        <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                            <div style="width:42px;height:42px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;">
                                <x-heroicon-o-calculator style="width:20px;height:20px;color:#2563eb;" />
                            </div>
                            <div>
                                <p style="font-size:22px;font-weight:800;color:#1f2937;margin:0;line-height:1.1;">{{ $nilaiSiswa }}</p>
                                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">Nilai</p>
                            </div>
                        </div>
                        <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                            <div style="width:42px;height:42px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                                <x-heroicon-o-check-circle style="width:20px;height:20px;color:#16a34a;" />
                            </div>
                            <div>
                                <p style="font-size:22px;font-weight:800;color:#1f2937;margin:0;line-height:1.1;">{{ $benar }}</p>
                                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">Jawaban Benar</p>
                            </div>
                        </div>
                        <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
                            <div style="width:42px;height:42px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                                <x-heroicon-o-x-circle style="width:20px;height:20px;color:#dc2626;" />
                            </div>
                            <div>
                                <p style="font-size:22px;font-weight:800;color:#1f2937;margin:0;line-height:1.1;">{{ $salah }}</p>
                                <p style="font-size:12px;color:#9ca3af;margin:3px 0 0;">Jawaban Salah</p>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach ($data['soal'] as $no => $soal)
                            @php
                                $jawSiswa    = $soal['jawaban_siswa'];
                                $kunci       = $soal['kunci'];
                                $isBenar     = $soal['is_benar'];
                                $borderColor = $isBenar === null ? '#e5e7eb' : ($isBenar ? '#86efac' : '#fca5a5');
                                $bgColor     = $isBenar === null ? '#fff'    : ($isBenar ? '#f0fdf4' : '#fff5f5');
                            @endphp
                            <div style="background:{{ $bgColor }};border:1.5px solid {{ $borderColor }};border-radius:14px;padding:20px;">
                                <div style="display:flex;align-items:flex-start;gap:14px;">
                                    <div style="width:32px;height:32px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                                        background:{{ $isBenar === null ? '#f3f4f6' : ($isBenar ? '#dcfce7' : '#fee2e2') }};
                                        font-weight:700;font-size:13px;
                                        color:{{ $isBenar === null ? '#6b7280' : ($isBenar ? '#16a34a' : '#dc2626') }};">
                                        {{ $no + 1 }}
                                    </div>
                                    <div style="flex:1;">
                                        <p style="font-weight:600;color:#1f2937;margin:0 0 14px;font-size:14px;">{{ $soal['soal'] }}</p>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
                                            @foreach (['a' => $soal['opsi_a'], 'b' => $soal['opsi_b'], 'c' => $soal['opsi_c'], 'd' => $soal['opsi_d']] as $huruf => $opsi)
                                                @php
                                                    $isKunci   = strtolower($kunci) === $huruf;
                                                    $isDipilih = strtolower($jawSiswa ?? '') === $huruf;
                                                    $opsBg     = $isKunci ? '#dcfce7' : ($isDipilih && !$isKunci ? '#fee2e2' : '#f9fafb');
                                                    $opsBorder = $isKunci ? '#86efac' : ($isDipilih && !$isKunci ? '#fca5a5' : '#e5e7eb');
                                                    $opsColor  = $isKunci ? '#15803d' : ($isDipilih && !$isKunci ? '#dc2626' : '#374151');
                                                @endphp
                                                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:{{ $opsBg }};border:1.5px solid {{ $opsBorder }};">
                                                    <span style="width:22px;height:22px;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;
                                                        background:{{ $isKunci ? '#16a34a' : ($isDipilih && !$isKunci ? '#dc2626' : '#e5e7eb') }};
                                                        color:{{ ($isKunci || ($isDipilih && !$isKunci)) ? '#fff' : '#6b7280' }};">
                                                        {{ strtoupper($huruf) }}
                                                    </span>
                                                    <span style="font-size:13px;color:{{ $opsColor }};font-weight:{{ ($isKunci || $isDipilih) ? '600' : '400' }};">{{ $opsi }}</span>
                                                    @if ($isKunci)
                                                        <x-heroicon-o-check-circle style="width:14px;height:14px;color:#16a34a;margin-left:auto;flex-shrink:0;" />
                                                    @elseif ($isDipilih && !$isKunci)
                                                        <x-heroicon-o-x-circle style="width:14px;height:14px;color:#dc2626;margin-left:auto;flex-shrink:0;" />
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div style="display:flex;align-items:center;gap:16px;font-size:12px;">
                                            <span style="color:#6b7280;">
                                                Jawaban siswa: <strong style="color:{{ $isBenar ? '#16a34a' : '#dc2626' }};">{{ $jawSiswa ? strtoupper($jawSiswa) : '-' }}</strong>
                                            </span>
                                            <span style="color:#6b7280;">
                                                Kunci: <strong style="color:#16a34a;">{{ strtoupper($kunci) }}</strong>
                                            </span>
                                            <span style="color:#6b7280;">
                                                Poin: <strong style="color:#2563eb;">{{ $soal['poin'] }}</strong>
                                            </span>
                                            @if ($isBenar !== null)
                                                <span style="margin-left:auto;padding:3px 10px;border-radius:999px;font-weight:600;background:{{ $isBenar ? '#dcfce7' : '#fee2e2' }};color:{{ $isBenar ? '#16a34a' : '#dc2626' }};">
                                                    {{ $isBenar ? 'Benar' : 'Salah' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                        <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                        <p style="color:#6b7280;font-weight:500;">Siswa belum mengerjakan soal.</p>
                    </div>
                @endif
            @endif
        @endif

    @endif

</div>
</x-filament-panels::page>