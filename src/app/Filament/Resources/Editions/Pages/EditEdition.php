<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Services\NewsletterRenderer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEdition extends EditRecord
{
    protected static string $resource = EditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('توليد الصورة')
                ->icon('heroicon-o-photo')
                ->color('success')
                ->action(function () {
                    $edition = $this->record->load(['news', 'recommendations', 'events']);

                    if ($edition->news->isEmpty()) {
                        Notification::make()
                            ->title('لا توجد أخبار في هذا العدد')
                            ->body('أضف خبرًا واحدًا على الأقل قبل التوليد.')
                            ->warning()
                            ->send();
                        return;
                    }

                    app(NewsletterRenderer::class)->render($edition);
                    $url = '/storage/newsletters/edition-' . $edition->issue_number . '.png';

                    Notification::make()
                        ->title('تم توليد صورة النشرة')
                        ->body('الصورة جاهزة — افتحها من الرابط أدناه.')
                        ->success()
                        ->actions([
                            Action::make('open')
                                ->label('فتح الصورة')
                                ->url($url, shouldOpenInNewTab: true),
                        ])
                        ->persistent()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
