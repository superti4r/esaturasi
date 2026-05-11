<x-filament-panels::page>
<div style="font-family:inherit;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#9ca3af;flex-wrap:wrap;margin-bottom:24px;">
        <button wire:click="backToKelas" style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
            onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
            Pilih Kelas
        </button>

        @if ($level !== 'kelas')
            <span style="color:#d1d5db;">/</span>
            <button wire:click="backToMapel" style="font-weight:600;color:#6b7280;background:none;border:none;cursor:pointer;padding:0;"
                onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#6b7280'">
                {{ $selectedKelasLabel }}
            </button>
        @endif

        @if ($level === 'rekap')
            <span style="color:#d1d5db;">/</span>
            <span style="font-weight:700;color:#1f2937;">{{ $selectedMapelLabel }}</span>
        @endif
    </div>

    {{-- LEVEL 1: PILIH KELAS --}}
    @if ($level === 'kelas')
        <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">Pilih kelas untuk melihat rekap nilai.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
            @forelse ($this->getKelasData() as $item)
                <button
                    wire:click="selectKelas({{ $item['id'] }}, '{{ addslashes($item['label']) }}')"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                >
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:42px;height:42px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <x-heroicon-o-academic-cap style="width:22px;height:22px;color:#2563eb;" />
                        </div>
                        <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $item['label'] }}</p>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                    <p style="color:#6b7280;font-weight:500;">Tidak ada kelas yang diajarkan.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- LEVEL 2: PILIH MAPEL --}}
    @if ($level === 'mapel')
        <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">Pilih mata pelajaran di kelas <strong>{{ $selectedKelasLabel }}</strong>.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
            @forelse ($this->getMapelData() as $item)
                <button
                    wire:click="selectMapel({{ $item['schedule_id'] }}, '{{ addslashes($item['label']) }}')"
                    style="text-align:left;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:20px;cursor:pointer;width:100%;"
                    onmouseover="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px #dbeafe'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                >
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:42px;height:42px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <x-heroicon-o-book-open style="width:22px;height:22px;color:#7c3aed;" />
                        </div>
                        <p style="font-weight:700;color:#1f2937;font-size:15px;margin:0;">{{ $item['label'] }}</p>
                    </div>
                </button>
            @empty
                <div style="grid-column:1/-1;background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                    <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                    <p style="color:#6b7280;font-weight:500;">Tidak ada mata pelajaran di kelas ini.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- LEVEL 3: TABEL REKAP --}}
    @if ($level === 'rekap')

        {{-- TAB --}}
        <div style="display:flex;gap:10px;margin-bottom:24px;">
            @foreach([
                'pretest'  => ['label' => 'Pre Test',  'icon' => 'heroicon-o-document-text'],
                'tugas'    => ['label' => 'Tugas',      'icon' => 'heroicon-o-clipboard-document-list'],
                'posttest' => ['label' => 'Post Test',  'icon' => 'heroicon-o-check-badge'],
            ] as $tab => $info)
                <button
                    wire:click="setTab('{{ $tab }}')"
                    style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:12px;font-size:14px;font-weight:600;border:1.5px solid {{ $activeTab === $tab ? '#2563eb' : '#e5e7eb' }};background:{{ $activeTab === $tab ? '#2563eb' : '#fff' }};color:{{ $activeTab === $tab ? '#fff' : '#6b7280' }};cursor:pointer;"
                >
                    <x-dynamic-component :component="$info['icon']" style="width:16px;height:16px;" />
                    {{ $info['label'] }}
                </button>
            @endforeach
        </div>

        @php $rekap = $this->getRekapData(); $columns = $rekap['columns']; $rows = $rekap['rows']; @endphp

        @if (empty($columns))
            <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;padding:60px 20px;text-align:center;">
                <x-heroicon-o-inbox style="width:40px;height:40px;color:#d1d5db;margin:0 auto 12px;" />
                <p style="color:#6b7280;font-weight:500;">Belum ada data {{ ucfirst($activeTab) }} untuk mapel ini.</p>
            </div>
        @else
            {{-- STATS --}}
            @php
                $nilaiAll = collect($rows)->flatMap(fn ($r) => array_values(array_filter($r['nilai'], fn ($v) => $v !== null)));
                $rataAll  = $nilaiAll->count() > 0 ? round($nilaiAll->avg()) : '-';
                $tertinggi = $nilaiAll->count() > 0 ? $nilaiAll->max() : '-';
                $terendah  = $nilaiAll->count() > 0 ? $nilaiAll->min() : '-';
            @endphp
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
                @foreach([
                    ['label' => 'Total Siswa', 'value' => count($rows),  'bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'heroicon-o-users'],
                    ['label' => 'Rata-rata',   'value' => $rataAll,       'bg' => '#ede9fe', 'color' => '#7c3aed', 'icon' => 'heroicon-o-calculator'],
                    ['label' => 'Tertinggi',   'value' => $tertinggi,     'bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'heroicon-o-arrow-trending-up'],
                    ['label' => 'Terendah',    'value' => $terendah,      'bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'heroicon-o-arrow-trending-down'],
                ] as $s)
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

            {{-- TABEL --}}
            <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:14px;overflow:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;min-width:600px;">
                    <thead>
                        <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                            <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;position:sticky;left:0;background:#f9fafb;">#</th>
                            <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;position:sticky;left:28px;background:#f9fafb;">NISN</th>
                            <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;position:sticky;left:120px;background:#f9fafb;">Nama Siswa</th>
                            @foreach ($columns as $i => $col)
                                <th style="padding:11px 16px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">
                                    T{{ $i + 1 }}<br>
                                    <span style="font-weight:400;font-size:10px;text-transform:none;letter-spacing:0;">{{ Str::limit($col['label'], 20) }}</span>
                                </th>
                            @endforeach
                            <th style="padding:11px 16px;text-align:center;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $i => $row)
                            <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <td style="padding:12px 16px;color:#d1d5db;position:sticky;left:0;background:inherit;">{{ $i + 1 }}</td>
                                <td style="padding:12px 16px;color:#6b7280;white-space:nowrap;position:sticky;left:28px;background:inherit;">{{ $row['nisn'] }}</td>
                                <td style="padding:12px 16px;position:sticky;left:120px;background:inherit;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <span style="font-size:11px;font-weight:700;color:#2563eb;">{{ strtoupper(substr($row['nama'], 0, 1)) }}</span>
                                        </div>
                                        <span style="font-weight:600;color:#1f2937;white-space:nowrap;">{{ $row['nama'] }}</span>
                                    </div>
                                </td>
                                @foreach ($columns as $col)
                                    @php
                                        $n    = $row['nilai'][$col['id']] ?? null;
                                        $bg   = $n === null ? '#f3f4f6' : ($n >= 80 ? '#dcfce7' : ($n >= 60 ? '#fef3c7' : '#fee2e2'));
                                        $fc   = $n === null ? '#9ca3af' : ($n >= 80 ? '#16a34a' : ($n >= 60 ? '#d97706' : '#dc2626'));
                                        $disp = $n === null ? '-' : $n;
                                    @endphp
                                    <td style="padding:12px 16px;text-align:center;">
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:26px;border-radius:8px;font-weight:700;font-size:12px;background:{{ $bg }};color:{{ $fc }};">
                                            {{ $disp }}
                                        </span>
                                    </td>
                                @endforeach
                                <td style="padding:12px 16px;text-align:center;">
                                    @php
                                        $r    = $row['rata'];
                                        $rbg  = $r === '-' ? '#f3f4f6' : ($r >= 80 ? '#dcfce7' : ($r >= 60 ? '#fef3c7' : '#fee2e2'));
                                        $rfc  = $r === '-' ? '#9ca3af' : ($r >= 80 ? '#16a34a' : ($r >= 60 ? '#d97706' : '#dc2626'));
                                    @endphp
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:26px;border-radius:8px;font-weight:700;font-size:12px;background:{{ $rbg }};color:{{ $rfc }};">
                                        {{ $r }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($columns) }}" style="padding:60px 20px;text-align:center;color:#9ca3af;">
                                    Belum ada siswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p style="font-size:11px;color:#9ca3af;margin-top:10px;">
                * Klik tombol <strong>Export Excel</strong> di kanan atas untuk mengunduh rekap 3 sheet (Pre Test · Tugas · Post Test).
            </p>
        @endif
    @endif

</div>
</x-filament-panels::page>