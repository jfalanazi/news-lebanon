<?php
namespace App\Filament\Resources\Events\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge(),
                TextColumn::make('start_date')
                    ->label('البداية')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('النهاية')
                    ->date()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
