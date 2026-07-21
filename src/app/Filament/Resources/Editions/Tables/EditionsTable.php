<?php
namespace App\Filament\Resources\Editions\Tables;
use App\Support\ArabicDate;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('edition_date')
                    ->label('التاريخ')
                    ->formatStateUsing(fn ($state): string => ArabicDate::full($state))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === 'published' ? 'منشور' : 'مسودة')
                    ->color(fn (?string $state): string => $state === 'published' ? 'success' : 'gray'),
                TextColumn::make('news_count')
                    ->label('عدد الأخبار')
                    ->counts('news'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                    ]),
            ])
            ->recordActions([
                Action::make('web')
                    ->label('صفحة الويب')
                    ->icon('heroicon-o-globe-alt')
                    ->color('gray')
                    ->url(fn ($record): string => url('/n/' . $record->issue_number))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
