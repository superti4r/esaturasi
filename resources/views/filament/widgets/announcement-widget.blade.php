<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Pengumuman Terbaru</x-slot>

        @php $announcements = $this->getAnnouncements(); @endphp

        @if ($announcements->isEmpty())
            <div style="text-align:center;padding:24px 0;">
                <p style="color:#9ca3af;font-size:14px;">Tidak ada pengumuman dalam 1 bulan terakhir.</p>
            </div>
        @else
            <div style="display:flex;flex-direction:column;">
                @foreach ($announcements as $announcement)
                    <div style="padding:12px 0;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:600;color:#1f2937;font-size:14px;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $announcement->title }}
                            </p>
                            <p style="font-size:11px;color:#9ca3af;margin:3px 0 0;">
                                {{ $announcement->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>