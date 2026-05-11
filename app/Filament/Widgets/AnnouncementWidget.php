<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnnouncementWidget extends Widget
{
    protected static string $view = 'filament.widgets.announcement-widget';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Administrator');
    }

    public function getAnnouncements()
    {
        return Announcement::where('created_at', '>=', Carbon::now()->subMonth())
            ->latest()
            ->take(3)
            ->get();
    }
}