<?php

namespace App\Filament\Widgets;

use App\Models\Edition;
use App\Models\NewsCandidate;
use App\Models\NewsItem;
use App\Models\Source;
use App\Support\ArabicDate;
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
        $totalEditions = Edition::count();
        $totalNews = NewsItem::count();
        $activeSources = Source::where('is_active', true)->count();
        $monthEditions = Edition::whereYear('edition_date', now()->year)
            ->whereMonth('edition_date', now()->month)->count();

        return [
            Stat::make('آخر عدد', $latest ? '#' . $latest->issue_number : '—')
                ->description($latest
                    ? (($latest->status === 'published' ? 'منشور' : 'مسودة') . ' · ' . ArabicDate::short($latest->edition_date))
                    : 'لا يوجد أعداد بعد')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color($latest && $latest->status === 'published' ? 'success' : 'gray'),

            Stat::make('أخبار مرشّحة منتظرة', $pending)
                ->description('جاهزة للانتقاء والإضافة للعدد')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($pending > 0 ? 'warning' : 'gray'),

            Stat::make('الأعداد المنشورة', $published)
                ->description('من إجمالي ' . $totalEditions . ' عدد')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('إجمالي الأخبار', $totalNews)
                ->description('عبر كل الأعداد')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('المصادر المفعّلة', $activeSources)
                ->description('مصادر RSS نشطة')
                ->descriptionIcon('heroicon-m-rss')
                ->color($activeSources > 0 ? 'success' : 'danger'),

            Stat::make('أعداد هذا الشهر', $monthEditions)
                ->description(ArabicDate::monthYear(now()))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('gray'),
        ];
    }
}
