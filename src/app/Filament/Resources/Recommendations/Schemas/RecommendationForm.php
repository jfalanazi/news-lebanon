<?php
namespace App\Filament\Resources\Recommendations\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class RecommendationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('edition_id')
                    ->label('العدد')
                    ->relationship('edition', 'edition_date')
                    ->required(),
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
                    ->required(),
                TextInput::make('area')
                    ->label('المنطقة'),
                TextInput::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                TextInput::make('position')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
