<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Editions\EditionResource;
use App\Models\Edition;
use App\Services\NewsPicker;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

/** بطاقة الإجراءات السريعة أعلى لوحة التحكم: آخر عدد + نشرة اليوم */
class NashraActions extends Widget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.nashra-actions';

    public function getViewData(): array
    {
        $latest = Edition::orderByDesc('edition_date')->first();
        $img = null;

        if ($latest) {
            $path = storage_path('app/public/newsletters/edition-' . $latest->issue_number . '.png');
            if (file_exists($path)) {
                $img = '/storage/newsletters/edition-' . $latest->issue_number . '.png?t=' . filemtime($path);
            }
        }

        return [
            'latest'   => $latest,
            'img'      => $img,
            'hasToday' => Edition::whereDate('edition_date', now()->toDateString())->exists(),
            'editUrl'  => $latest ? EditionResource::getUrl('edit', ['record' => $latest]) : null,
            'listUrl'  => EditionResource::getUrl('index'),
        ];
    }

    /** نفس مسار «نشرة اليوم»: إنشاء/تعبئة عدد اليوم ثم فتحه للتحرير */
    public function today(): void
    {
        $edition = Edition::firstOrCreate(
            ['edition_date' => now()->toDateString()],
            ['issue_number' => Edition::nextIssueNumber(), 'status' => 'draft'],
        );

        try {
            $added = app(NewsPicker::class)->fill($edition);

            Notification::make()
                ->title($added > 0 ? "تم تجهيز نشرة اليوم — {$added} أخبار ✨" : 'العدد جاهز — لا أخبار جديدة غير مكررة')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('أُنشئ العدد، لكن تعذّر التوليد الذكي')
                ->body($e->getMessage())
                ->warning()
                ->send();
        }

        $this->redirect(EditionResource::getUrl('edit', ['record' => $edition]));
    }
}
