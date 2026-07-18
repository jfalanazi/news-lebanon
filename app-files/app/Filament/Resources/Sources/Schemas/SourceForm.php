<?php
namespace App\Filament\Resources\Sources\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
class SourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم المصدر')
                    ->required(),
                TextInput::make('domain')
                    ->label('النطاق (الدومين)')
                    ->placeholder('annahar.com'),
                TextInput::make('url')
                    ->label('الرابط / RSS')
                    ->url()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('مُفعّل')
                    ->default(true),
            ]);
    }
}
