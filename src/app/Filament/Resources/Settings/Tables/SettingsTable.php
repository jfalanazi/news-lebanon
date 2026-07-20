<?php
namespace App\Filament\Resources\Settings\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // نخفي الإعدادات التقنية المربكة — تُدار المدينة من زر «المدينة» أعلى الصفحة
            ->modifyQueryUsing(fn ($query) => $query->whereNotIn('key', [
                'weather_lat', 'weather_lng', 'prayer_method', 'prayer_fajr_angle',
                'prayer_isha_angle', 'issue_anchor_number', 'issue_anchor_date',
            ]))
            ->columns([
                TextColumn::make('key')
                    ->label('الإعداد')
                    ->formatStateUsing(fn (string $state): string => \App\Models\Setting::labelFor($state))
                    ->description(fn ($record): string => $record->key)
                    ->searchable(),
                TextColumn::make('value')
                    ->label('القيمة')
                    ->wrap()
                    ->searchable(),
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
