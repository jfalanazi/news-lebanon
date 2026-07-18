<?php
namespace App\Filament\Resources\NewsItems\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class NewsItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                TextColumn::make('position')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('العنوان')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('التصنيف')
                    ->searchable(),
                TextColumn::make('source_name')
                    ->label('المصدر')
                    ->searchable(),
                TextColumn::make('priority')
                    ->label('الأولوية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'breaking' => 'عاجل', 'important' => 'مهم', default => 'عادي',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'breaking' => 'danger', 'important' => 'warning', default => 'gray',
                    }),
                TextColumn::make('edition.edition_date')
                    ->label('العدد')
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
