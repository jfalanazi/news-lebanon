<?php
namespace App\Filament\Resources\Editions\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class EditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('edition_date', 'desc')
            ->columns([
                TextColumn::make('issue_number')
                    ->label('رقم العدد')
                    ->sortable(),
                TextColumn::make('edition_date')
                    ->label('التاريخ')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'منشور', 'approved' => 'معتمد',
                        'in_review' => 'قيد المراجعة', default => 'مسودة',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success', 'approved' => 'info',
                        'in_review' => 'warning', default => 'gray',
                    }),
                TextColumn::make('news_count')
                    ->label('عدد الأخبار')
                    ->counts('news'),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
