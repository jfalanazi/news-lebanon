<?php
namespace App\Filament\Resources\Events\Schemas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('edition_id')
                    ->label('العدد')
                    ->relationship('edition', 'edition_date'),
                Select::make('category')
                    ->label('التصنيف')
                    ->options([
                        'ثقافي' => 'ثقافي',
                        'سياحي' => 'سياحي',
                        'فني' => 'فني',
                        'رياضي' => 'رياضي',
                        'أخرى' => 'أخرى',
                    ]),
                TextInput::make('title')
                    ->label('عنوان الفعالية')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('تاريخ البداية'),
                DatePicker::make('end_date')
                    ->label('تاريخ النهاية (فارغ = يوم واحد)'),
                TextInput::make('persist_days')
                    ->label('يبقى (أيام)')
                    ->numeric()
                    ->default(1),
                TextInput::make('position')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
