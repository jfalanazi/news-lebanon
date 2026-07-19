<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Services\NewsFetcher;
use App\Services\NewsletterCaption;
use App\Services\NewsletterRenderer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEdition extends EditRecord
{
    protected static string $resource = EditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // سحب أخبار اليوم من مصادر RSS إلى المجموعة المرشّحة
            Action::make('fetch')
                ->label('اسحب أخبار اليوم')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $report = app(NewsFetcher::class)->fetchAll();

                    if (empty($report)) {
                        Notification::make()
                            ->title('لا توجد مصادر مفعّلة')
                            ->body('أضف روابط RSS وفعّلها من شاشة «المصادر».')
                            ->warning()
                            ->send();

                        return;
                    }

                    $total = collect($report)->filter(fn ($v) => is_int($v))->sum();

                    Notification::make()
                        ->title('تم سحب الأخبار')
                        ->body("أُضيف {$total} خبرًا إلى «الأخبار المرشّحة» — راجعها وأضف المناسب للعدد.")
                        ->success()
                        ->send();
                }),

            // نشر كامل: توليد الصورة + نص واتساب قابل للنسخ + تعليم العدد كمنشور
            Action::make('publish')
                ->label('نشر ومشاركة')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->modalHeading('نشر النشرة ومشاركتها')
                ->modalDescription('سيتم توليد صورة النشرة وتجهيز نص واتساب جاهز للنسخ، وتعليم العدد كمنشور.')
                ->modalSubmitActionLabel('توليد ونشر')
                ->fillForm(function () {
                    $edition = $this->record->load(['news', 'recommendations', 'events']);

                    return [
                        'caption' => $edition->news->isEmpty()
                            ? 'أضف خبرًا واحدًا على الأقل قبل النشر.'
                            : app(NewsletterCaption::class)->build($edition),
                    ];
                })
                ->form([
                    Textarea::make('caption')
                        ->label('نص المشاركة (واتساب) — انسخه')
                        ->rows(14)
                        ->readOnly()
                        ->extraInputAttributes(['style' => 'direction:rtl;line-height:1.9']),
                ])
                ->action(function () {
                    $edition = $this->record->load(['news', 'recommendations', 'events']);

                    if ($edition->news->isEmpty()) {
                        Notification::make()
                            ->title('لا توجد أخبار في هذا العدد')
                            ->body('أضف خبرًا واحدًا على الأقل قبل النشر.')
                            ->warning()
                            ->send();

                        return;
                    }

                    app(NewsletterRenderer::class)->render($edition);

                    $edition->update([
                        'status'       => 'published',
                        'published_at' => now(),
                    ]);

                    $url = '/storage/newsletters/edition-' . $edition->issue_number . '.png';

                    Notification::make()
                        ->title('تم النشر بنجاح')
                        ->body('الصورة جاهزة ونص المشاركة مُعبّأ في الحقل أعلاه.')
                        ->success()
                        ->actions([
                            Action::make('open')
                                ->label('فتح الصورة')
                                ->url($url, shouldOpenInNewTab: true),
                        ])
                        ->persistent()
                        ->send();
                }),

            // توليد الصورة فقط (بدون تغيير الحالة)
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
