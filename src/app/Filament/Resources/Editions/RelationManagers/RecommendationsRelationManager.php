<?php

namespace App\Filament\Resources\Editions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                CreateAction::make()->label('إضافة توصية'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('لا توجد توصيات بعد');
    }
}
