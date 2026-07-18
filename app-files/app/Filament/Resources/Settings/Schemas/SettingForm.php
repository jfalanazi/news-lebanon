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
                    ->label('المفتاح')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('value')
                    ->label('القيمة')
                    ->columnSpanFull(),
            ]);
    }
}
