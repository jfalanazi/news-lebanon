<?php
namespace App\Filament\Resources\Sources\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
class SourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('domain')
                    ->label('النطاق')
                    ->searchable(),
                // صحة المصدر: مفتاح التفعيل وحده لا يكشف مصدرًا مات بصمت
                TextColumn::make('last_fetched_at')
                    ->label('آخر سحب')
                    ->badge()
                    ->formatStateUsing(fn ($state, $record): string => $record->last_error
                        ? '⚠️ فشل · ' . $state->locale('ar')->diffForHumans()
                        : $state->locale('ar')->diffForHumans() . ' · ' . (int) ($record->last_fetch_count ?? 0) . ' خبر')
                    ->color(fn ($record): string => $record->last_error ? 'danger' : 'success')
                    ->tooltip(fn ($record): ?string => $record->last_error)
                    ->placeholder('لم يُسحب بعد'),
                ToggleColumn::make('is_active')
                    ->label('مُفعّل'),
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
