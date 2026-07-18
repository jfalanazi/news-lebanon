<?php
namespace App\Filament\Resources\Editions\Schemas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class EditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('issue_number')
                    ->label('رقم العدد')
                    ->required()
                    ->numeric(),
                DatePicker::make('edition_date')
                    ->label('تاريخ العدد')
                    ->required(),
                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'in_review' => 'قيد المراجعة',
                        'approved' => 'معتمد',
                        'published' => 'منشور',
                    ])
                    ->default('draft')
                    ->required(),
                TextInput::make('quote')
                    ->label('عبارة التذييل')
                    ->columnSpanFull(),
                TextInput::make('caption_link')
                    ->label('رابط التعليق / QR')
                    ->url()
                    ->columnSpanFull(),
            ]);
    }
}
