<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Models\Edition;
use App\Services\NewsPicker;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListEditions extends ListRecords
{
    protected static string $resource = EditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // نشرة اليوم: ينشئ عدد اليوم ويعبّئه بالذكاء ويفتحه — بضغطة واحدة
            Action::make('today')
                ->label('نشرة اليوم')
                ->icon('heroicon-o-bolt')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('تجهيز نشرة اليوم')
                ->modalDescription('ينشئ عدد اليوم إن لم يوجد، يعبّئه بأخبار مُنتقاة بالذكاء، ويفتحه للتحرير.')
                ->modalSubmitActionLabel('جهّز وافتح')
                ->action(function () {
                    $edition = Edition::firstOrCreate(
                        ['edition_date' => now()->toDateString()],
                        ['issue_number' => Edition::nextIssueNumber(), 'status' => 'draft'],
                    );

                    try {
                        $added = app(NewsPicker::class)->fill($edition);

                        Notification::make()
                            ->title($added > 0 ? "تم تجهيز نشرة اليوم — {$added} أخبار ✨" : 'أُنشئ العدد — لا أخبار جديدة غير مكررة')
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
                }),

            // إضافة يدوية — ثانوي (زر ممتلئ واحد فقط في الشاشة: نشرة اليوم)
            CreateAction::make()
                ->outlined(),
        ];
    }
}
