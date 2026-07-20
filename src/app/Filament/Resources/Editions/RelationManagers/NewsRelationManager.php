<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use App\Models\NewsCandidate;
use App\Services\AiNewsCurator;
use App\Services\AiSuggester;
use App\Services\NewsFetcher;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class NewsRelationManager extends RelationManager
{
    protected static string $relationship = 'news';

    protected static ?string $title = 'الأخبار';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('العنوان')
                ->required()
                ->columnSpanFull(),

            // محتوى الخبر الكامل — أساس الإدخال اليدوي
            Textarea::make('body')
                ->label('محتوى الخبر')
                ->rows(6)
                ->helperText('النص الكامل للخبر — يظهر في صفحة العدد لمن يريد التفاصيل.')
                ->columnSpanFull(),

            // النبذة أسفل المحتوى، وبجانبها زر توليد يلخّص من المحتوى
            Textarea::make('excerpt')
                ->label('النبذة (سطر مختصر يظهر في النشرة)')
                ->rows(2)
                ->maxLength(500)
                ->hintAction(
                    Action::make('summarize')
                        ->label('✨ توليد من المحتوى')
                        ->action(function ($get, $set) {
                            $body = trim((string) $get('body'));
                            if ($body === '') {
                                Notification::make()
                                    ->title('أضف «محتوى الخبر» أولًا')
                                    ->body('التلخيص الذكي يحتاج نص الخبر ليختصر منه.')
                                    ->warning()->send();

                                return;
                            }

                            try {
                                $summary = app(AiSuggester::class)->summarize((string) $get('title'), $body);
                                if ($summary !== '') {
                                    $set('excerpt', $summary);
                                }
                            } catch (\Throwable $e) {
                                Notification::make()->title('تعذّر التوليد')
                                    ->body($e->getMessage())->danger()->send();
                            }
                        })
                )
                ->columnSpanFull(),

            Select::make('priority')
                ->label('الأولوية')
                ->options(['normal' => 'عادي', 'important' => 'مهم', 'breaking' => 'عاجل'])
                ->default('normal')
                ->required(),

            TextInput::make('url')
                ->label('رابط الخبر')
                ->helperText('اختياري — يُشتق منه اسم المصدر تلقائيًا.')
                ->columnSpanFull(),

            TextInput::make('category')
                ->label('التصنيف'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'سياسة' => 'info', 'اقتصاد' => 'success', 'أمن' => 'danger',
                        'رياضة' => 'warning', 'مجتمع' => 'primary', 'دولي' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('priority')
                    ->label('الأولوية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'breaking' => 'عاجل', 'important' => 'مهم', default => 'عادي',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'breaking' => 'danger', 'important' => 'warning', default => 'gray',
                    }),
                ToggleColumn::make('active')
                    ->label('مُفعّل'),
                TextColumn::make('source_name')
                    ->label('المصدر')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                TextColumn::make('ai_generated')
                    ->label('الأصل')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? '✨ ذكاء' : '✍️ يدوي')
                    ->color(fn ($state): string => $state ? 'primary' : 'gray'),
            ])
            ->recordClasses(fn ($record) => $record->active ? null : 'nashra-dim')
            ->headerActions([
                CreateAction::make()
                    ->label('➕ إضافة خبر')
                    ->modalHeading('إضافة خبر')
                    ->color('primary')
                    ->button(),
                Action::make('aiGenerate')
                    ->label('توليد ذكي')
                    ->icon('heroicon-o-sparkles')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('توليد ذكي')
                    ->modalDescription('سيتصل بالذكاء (تكلفة بسيطة جدًا). متابعة؟')
                    ->action(function () {
                        $edition = $this->getOwnerRecord();

                        app(NewsFetcher::class)->fetchAll();

                        $batch = NewsCandidate::where('used', false)
                            ->where('ai_processed', false)
                            ->latest()->take(10)->get();

                        if ($batch->isNotEmpty()) {
                            try {
                                app(AiNewsCurator::class)->process($batch);
                            } catch (\Throwable $e) {
                                Notification::make()->title('تعذّر التوليد الذكي')
                                    ->body($e->getMessage())->danger()->persistent()->send();

                                return;
                            }
                        }

                        $toAdd = NewsCandidate::where('used', false)
                            ->where('ai_processed', true)
                            ->latest()->take(7)->get();

                        if ($toAdd->isEmpty()) {
                            Notification::make()->title('لا توجد أخبار جديدة')
                                ->body('كل الأخبار مستخدمة أو لا مصادر مفعّلة.')->warning()->send();

                            return;
                        }

                        $pos = (int) $edition->news()->max('position');
                        $added = 0;
                        foreach ($toAdd as $c) {
                            $edition->news()->create([
                                'category'     => $c->category,
                                'url'          => $c->url,
                                'source_name'  => $c->source_name,
                                'title'        => $c->title,
                                'excerpt'      => $c->excerpt,
                                'priority'     => $c->priority ?: 'normal',
                                'position'     => ++$pos,
                                'ai_generated' => true,
                            ]);
                            $c->update(['used' => true]);
                            $added++;
                        }

                        Notification::make()->title("أُضيف {$added} خبرًا بالذكاء")->success()->send();
                    }),
            ])
            ->recordActions([
                Action::make('up')
                    ->label('')
                    ->icon('heroicon-o-chevron-up')
                    ->color('gray')
                    ->tooltip('رفع لأعلى')
                    ->action(function ($record) {
                        $above = $record->edition->news()
                            ->where('position', '<', $record->position)
                            ->orderByDesc('position')->first();
                        if ($above) {
                            $p = $record->position;
                            $record->update(['position' => $above->position]);
                            $above->update(['position' => $p]);
                        }
                    }),
                Action::make('down')
                    ->label('')
                    ->icon('heroicon-o-chevron-down')
                    ->color('gray')
                    ->tooltip('تنزيل لأسفل')
                    ->action(function ($record) {
                        $below = $record->edition->news()
                            ->where('position', '>', $record->position)
                            ->orderBy('position')->first();
                        if ($below) {
                            $p = $record->position;
                            $record->update(['position' => $below->position]);
                            $below->update(['position' => $p]);
                        }
                    }),
                EditAction::make()->modalHeading('تعديل الخبر'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->emptyStateHeading('لا توجد أخبار بعد')
            ->emptyStateDescription('اضغط «إضافة خبر» لإضافة أول خبر لهذا العدد.');
    }
}
