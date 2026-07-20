<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use App\Services\AiSuggester;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecommendationsRelationManager extends RelationManager
{
    protected static string $relationship = 'recommendations';

    protected static ?string $title = 'التوصيات';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->label('النوع')
                ->options([
                    'restaurant' => 'مطعم',
                    'landmark' => 'معلم',
                    'park' => 'منتزه',
                    'cafe' => 'مقهى',
                ])
                ->default('restaurant')
                ->required(),
            TextInput::make('name')
                ->label('الاسم')
                ->required()
                ->columnSpanFull(),
            TextInput::make('area')
                ->label('المنطقة'),
            TextInput::make('description')
                ->label('الوصف (اختياري)')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('لإعادة ترتيب التوصيات: اسحب الصف من مقبض السحب ⠿ على جانبه.')
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'restaurant' => 'مطعم', 'landmark' => 'معلم',
                        'park' => 'منتزه', 'cafe' => 'مقهى', default => $state,
                    }),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('area')
                    ->label('المنطقة'),
            ])
            ->headerActions([
                Action::make('aiSuggest')
                    ->label('اقتراح ذكي')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->action(function () {
                        $edition = $this->getOwnerRecord();

                        try {
                            $rows = app(AiSuggester::class)->recommendations(3);
                        } catch (\Throwable $e) {
                            Notification::make()->title('تعذّر الاقتراح الذكي')
                                ->body($e->getMessage())->danger()->persistent()->send();

                            return;
                        }

                        $pos = (int) $edition->recommendations()->max('position');
                        $added = 0;
                        foreach ($rows as $r) {
                            if (empty($r['name'])) {
                                continue;
                            }
                            $edition->recommendations()->create([
                                'type'        => in_array($r['type'] ?? '', ['restaurant', 'landmark', 'park', 'cafe'], true) ? $r['type'] : 'restaurant',
                                'name'        => $r['name'],
                                'area'        => $r['area'] ?? null,
                                'description' => $r['description'] ?? null,
                                'position'    => ++$pos,
                            ]);
                            $added++;
                        }

                        Notification::make()->title("أُضيف {$added} توصية بالذكاء")->success()->send();
                    }),
                CreateAction::make()->label('إضافة توصية'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('لا توجد توصيات بعد');
    }
}
