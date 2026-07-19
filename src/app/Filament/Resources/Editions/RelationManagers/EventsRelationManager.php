<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use App\Services\AiSuggester;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'الفعاليات';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('عنوان الفعالية')
                ->required()
                ->columnSpanFull(),
            Select::make('category')
                ->label('التصنيف')
                ->options([
                    'ثقافي' => 'ثقافي',
                    'سياحي' => 'سياحي',
                    'فني' => 'فني',
                    'رياضي' => 'رياضي',
                    'أخرى' => 'أخرى',
                ]),
            DatePicker::make('start_date')
                ->label('تاريخ البداية'),
            DatePicker::make('end_date')
                ->label('تاريخ النهاية (فارغ = يوم واحد)'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge(),
                TextColumn::make('title')
                    ->label('العنوان')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('البداية')
                    ->date(),
            ])
            ->headerActions([
                Action::make('aiSuggest')
                    ->label('اقتراح ذكي')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->action(function () {
                        $edition = $this->getOwnerRecord();

                        try {
                            $rows = app(AiSuggester::class)->events(3);
                        } catch (\Throwable $e) {
                            Notification::make()->title('تعذّر الاقتراح الذكي')
                                ->body($e->getMessage())->danger()->persistent()->send();

                            return;
                        }

                        $today = now()->toDateString();
                        // نقبل التواريخ الصحيحة المستقبلية فقط، وإلا نتركها فارغة
                        $futureDate = fn ($v) => is_string($v)
                            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)
                            && $v >= $today
                            ? $v : null;

                        $pos = (int) $edition->events()->max('position');
                        $added = 0;
                        foreach ($rows as $r) {
                            if (empty($r['title'])) {
                                continue;
                            }
                            $edition->events()->create([
                                'title'      => $r['title'],
                                'category'   => in_array($r['category'] ?? '', ['ثقافي', 'سياحي', 'فني', 'رياضي'], true) ? $r['category'] : 'ثقافي',
                                'start_date' => $futureDate($r['start'] ?? null),
                                'end_date'   => $futureDate($r['end'] ?? null),
                                'position'   => ++$pos,
                            ]);
                            $added++;
                        }

                        Notification::make()->title("أُضيف {$added} فعالية بالذكاء")
                            ->body('راجِع التواريخ — قد تحتاج تدقيقًا.')->success()->send();
                    }),
                CreateAction::make()->label('إضافة فعالية'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('لا توجد فعاليات بعد');
    }
}
