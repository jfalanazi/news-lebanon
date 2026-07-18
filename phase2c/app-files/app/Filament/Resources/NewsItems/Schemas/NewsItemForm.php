<?php
namespace App\Filament\Resources\NewsItems\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class NewsItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('edition_id')
                    ->label('العدد')
                    ->relationship('edition', 'edition_date')
                    ->required(),
                TextInput::make('category')
                    ->label('التصنيف'),
                TextInput::make('url')
                    ->label('رابط الخبر')
                    ->url()
                    ->helperText('يُشتق منه اسم المصدر تلقائيًا'),
                TextInput::make('source_name')
                    ->label('اسم المصدر (اختياري)')
                    ->placeholder('يُملأ تلقائيًا من الرابط'),
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('excerpt')
                    ->label('النبذة')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
                Select::make('priority')
                    ->label('الأولوية')
                    ->options(['normal' => 'عادي', 'important' => 'مهم', 'breaking' => 'عاجل'])
                    ->default('normal')
                    ->required(),
                TextInput::make('position')
                    ->label('الترتيب')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
