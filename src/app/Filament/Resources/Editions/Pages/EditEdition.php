<?php

namespace App\Filament\Resources\Editions\Pages;

use App\Filament\Resources\Editions\EditionResource;
use App\Services\NewsPicker;
use App\Services\NewsletterCaption;
use App\Services\NewsletterRenderer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditEdition extends EditRecord
{
    protected static string $resource = EditionResource::class;

    /**
     * مرحلة العدد الحالية — تحدد الزر الأساسي الوحيد في الشاشة:
     * empty (لا أخبار) → ready (أخبار بلا صورة) → rendered (صورة جاهزة) → published (منشور)
     */
    protected function editionStage(): string
    {
        $record = $this->getRecord();

        if ($record->status === 'published') {
            return 'published';
        }
        if (! $record->news()->exists()) {
            return 'empty';
        }

        return $this->imageExists() ? 'rendered' : 'ready';
    }

    protected function imageExists(): bool
    {
        return file_exists(storage_path(
            'app/public/newsletters/edition-' . $this->getRecord()->issue_number . '.png'
        ));
    }

    /** مؤشّر الخطوات تحت عنوان الصفحة */
    public function getSubheading(): ?string
    {
        return match ($this->editionStage()) {
            'empty'     => 'الخطوة ١ من ٣ — أضف الأخبار (يدويًا أو بالتوليد الذكي)',
            'ready'     => 'الخطوة ٢ من ٣ — راجع المحتوى والترتيب ثم ولّد الصورة',
            'rendered'  => 'الخطوة ٣ من ٣ — كل شيء جاهز: انشر وشارك',
            'published' => 'منشور ✓ — يمكنك المشاركة أو إعادة توليد الصورة بعد أي تعديل',
            default     => null,
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            // ١) توليد ذكي — الزر الأساسي عندما يكون العدد فارغًا
            Action::make('curate')
                ->label('توليد ذكي')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->outlined(fn (): bool => $this->editionStage() !== 'empty')
                ->requiresConfirmation()
                ->modalHeading('تعبئة العدد بالذكاء')
                ->modalDescription('يسحب آخر الأخبار من المصادر المفعّلة، ينتقيها بالذكاء، ويضيفها إلى هذا العدد.')
                ->modalSubmitActionLabel('اسحب وانتقِ')
                ->action(function () {
                    $edition = $this->getRecord();

                    try {
                        $added = app(NewsPicker::class)->fill($edition);

                        if ($added > 0) {
                            Notification::make()->title("تمت تعبئة العدد — {$added} أخبار ✨")->success()->send();
                        } else {
                            Notification::make()
                                ->title('لا جديد')
                                ->body('كل الأخبار مستخدمة أو مكررة أو لا مصادر مفعّلة.')
                                ->warning()
                                ->send();
                        }
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('تعذّر التوليد الذكي')
                            ->body($e->getMessage())
                            ->warning()
                            ->send();
                    }

                    $this->redirect(EditionResource::getUrl('edit', ['record' => $edition]));
                }),

            // ٢) توليد الصورة — الزر الأساسي عندما توجد أخبار بلا صورة
            Action::make('generate')
                ->label('توليد الصورة')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->outlined(fn (): bool => $this->editionStage() !== 'ready')
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
                        ->success()
                        ->actions([
                            Action::make('open')
                                ->label('فتح الصورة')
                                ->url($url, shouldOpenInNewTab: true),
                        ])
                        ->persistent()
                        ->send();

                    $this->redirect(EditionResource::getUrl('edit', ['record' => $edition]));
                }),

            // ٣) نشر ومشاركة — الزر الأساسي عندما تكون الصورة جاهزة، ويتحول لـ«مشاركة واتساب» بعد النشر
            Action::make('publish')
                ->label(fn (): string => $this->editionStage() === 'published' ? 'مشاركة واتساب' : 'نشر ومشاركة')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->outlined(fn (): bool => ! in_array($this->editionStage(), ['rendered', 'published']))
                ->modalHeading(fn (): string => $this->editionStage() === 'published' ? 'مشاركة النشرة' : 'نشر النشرة ومشاركتها')
                ->modalDescription('انسخ التعليق، حمّل الصورة وأرفقها في واتساب كـ«مستند» لتفادي الضغط.')
                ->modalSubmitActionLabel(fn (): string => $this->editionStage() === 'published' ? 'تم' : 'توليد ونشر')
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
                        ->rows(12)
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

                    // حارس جودة: لا نشر بتصنيف ناقص أو تجريبي
                    $bad = $edition->news->first(fn ($n) => in_array(trim((string) $n->category), ['', 'التصنيف'], true));
                    if ($bad) {
                        Notification::make()
                            ->title('تصنيف ناقص')
                            ->body('الخبر «' . Str::limit($bad->title, 40) . '» بلا تصنيف صحيح — عدّله قبل النشر.')
                            ->warning()
                            ->send();

                        return;
                    }

                    // عبارة تذييل تجريبية ← تسقط للعبارة الافتراضية من الإعدادات
                    if (str_contains((string) $edition->quote, 'تذييل') || str_contains((string) $edition->quote, 'تذيييل')) {
                        $edition->update(['quote' => null]);
                        $edition->refresh()->load(['news', 'recommendations', 'events']);
                    }

                    // توليد الصورة دائمًا لتعكس آخر التعديلات
                    app(NewsletterRenderer::class)->render($edition);

                    if ($edition->status !== 'published') {
                        $edition->update([
                            'status'       => 'published',
                            'published_at' => now(),
                        ]);
                    }

                    $url = '/storage/newsletters/edition-' . $edition->issue_number . '.png';

                    Notification::make()
                        ->title($edition->wasChanged('status') ? 'تم النشر بنجاح' : 'الصورة مُحدَّثة وجاهزة للمشاركة')
                        ->success()
                        ->actions([
                            Action::make('open')
                                ->label('فتح الصورة')
                                ->url($url, shouldOpenInNewTab: true),
                        ])
                        ->persistent()
                        ->send();

                    $this->redirect(EditionResource::getUrl('edit', ['record' => $edition]));
                }),

            // ٤) حذف — رابط هادئ، لا زر أحمر ممتلئ يزاحم إجراءات العمل
            DeleteAction::make()
                ->link()
                ->color('danger'),
        ];
    }
}
