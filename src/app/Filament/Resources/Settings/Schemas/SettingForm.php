<?php
namespace App\Filament\Resources\Settings\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('المفتاح التقني')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('value')
                    ->label('القيمة')
                    ->helperText(fn ($record): ?string => $record ? \App\Models\Setting::labelFor($record->key) : null)
                    ->columnSpanFull(),
            ]);
    }
}
