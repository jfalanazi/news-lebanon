<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Models\NewsCandidate;
use App\Models\NewsItem;
use App\Services\AiNewsCurator;
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
            // توليد وتعبئة ذكية: سحب + تنظيف بالذكاء + إضافة أفضل الأخبار لهذا العدد بضغطة
            Action::make('autofill')
                ->label('توليد وتعبئة ذكية')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('توليد وتعبئة العدد')
                ->modalDescription('سيسحب أخبار اليوم من مصادرك، ينظّفها ويصنّفها بالذكاء، ثم يضيف أفضل 7 أخبار لهذا العدد.')
                ->modalSubmitActionLabel('ابدأ')
                ->action(function () {
                    $edition = $this->record;

                    app(NewsFetcher::class)->fetchAll();

                    $batch = NewsCandidate::where('used', false)
                        ->where('ai_processed', false)
                        ->latest()->take(10)->get();

                    if ($batch->isNotEmpty()) {
                        try {
                            app(AiNewsCurator::class)->process($batch);
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('تعذّر التوليد الذكي')
                                ->body($e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();

                            return;
                        }
                    }

                    $toAdd = NewsCandidate::where('used', false)
                        ->where('ai_processed', true)
                        ->latest()->take(7)->get();

                    if ($toAdd->isEmpty()) {
                        Notification::make()
                            ->title('لا توجد أخبار جديدة')
                            ->body('كل الأخبار مستخدمة، أو لا توجد مصادر RSS مفعّلة.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $pos = (int) NewsItem::where('edition_id', $edition->id)->max('position');
                    $added = 0;
                    foreach ($toAdd as $c) {
                        NewsItem::create([
                            'edition_id'  => $edition->id,
                            'category'    => $c->category,
                            'url'         => $c->url,
                            'source_name' => $c->source_name,
                            'title'       => $c->title,
                            'excerpt'     => $c->excerpt,
                            'priority'    => $c->priority ?: 'normal',
                            'position'    => ++$pos,
                        ]);
                        $c->update(['used' => true]);
                        $added++;
                    }

                    Notification::make()
                        ->title('تمت التعبئة الذكية')
                        ->body("أُضيف {$added} خبرًا لهذا العدد. اضغط «↻ تحديث المعاينة» لرؤية النتيجة.")
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
