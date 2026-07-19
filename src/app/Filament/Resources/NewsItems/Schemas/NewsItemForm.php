<?php
namespace App\Filament\Resources\NewsItems\Schemas;

use App\Models\Edition;
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
                // العنوان أولًا — أهم حقل
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('excerpt')
                    ->label('النبذة')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                // العدد يُختار تلقائيًا لأحدث عدد
                Select::make('edition_id')
                    ->label('العدد')
                    ->relationship('edition', 'edition_date')
                    ->default(fn () => Edition::latest('edition_date')->value('id'))
                    ->required(),

                Select::make('priority')
                    ->label('الأولوية')
                    ->options(['normal' => 'عادي', 'important' => 'مهم', 'breaking' => 'عاجل'])
                    ->default('normal')
                    ->required(),

                TextInput::make('url')
                    ->label('رابط الخبر (اختياري)')
                    ->url()
                    ->helperText('يُشتق منه اسم المصدر تلقائيًا')
                    ->columnSpanFull(),

                TextInput::make('category')
                    ->label('التصنيف (اختياري)'),

                // الترتيب اختياري — يُملأ تلقائيًا
                TextInput::make('position')
                    ->label('الترتيب (اختياري)')
                    ->numeric()
                    ->default(0)
                    ->helperText('اتركه فارغًا ما لم ترغب بترتيب يدوي'),
            ]);
    }
}
