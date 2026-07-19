<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                CreateAction::make()->label('إضافة فعالية'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('لا توجد فعاليات بعد');
    }
}
