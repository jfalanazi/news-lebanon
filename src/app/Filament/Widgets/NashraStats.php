<?php

namespace App\Filament\Widgets;

use App\Models\Edition;
use App\Models\NewsCandidate;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NashraStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $latest = Edition::orderByDesc('edition_date')->first();
        $pending = NewsCandidate::where('used', false)->count();
        $published = Edition::where('status', 'published')->count();

        return [
            Stat::make('آخر عدد', $latest ? '#' . $latest->issue_number : '—')
                ->description($latest
                    ? (($latest->status === 'published' ? 'منشور' : 'مسودة') . ' · ' . Carbon::parse($latest->edition_date)->format('Y/m/d'))
                    : 'لا يوجد أعداد بعد')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color($latest && $latest->status === 'published' ? 'success' : 'gray'),

            Stat::make('أخبار مرشّحة منتظرة', $pending)
                ->description('جاهزة للانتقاء والإضافة للعدد')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($pending > 0 ? 'warning' : 'gray'),

            Stat::make('الأعداد المنشورة', $published)
                ->description('إجمالي ما نُشر')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
