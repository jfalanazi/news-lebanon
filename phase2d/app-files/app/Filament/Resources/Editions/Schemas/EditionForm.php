<?php
namespace App\Filament\Resources\Editions\Schemas;
use App\Models\Edition;
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
                    ->numeric()
                    ->default(fn () => Edition::nextIssueNumber())
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'رقم العدد مستخدم مسبقًا — اختر رقمًا آخر.',
                    ])
                    ->helperText('يُملأ تلقائيًا؛ يمكنك تعديله يدويًا.'),
                DatePicker::make('edition_date')
                    ->label('تاريخ العدد')
                    ->required()
                    ->default(now())
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'يوجد عدد لهذا التاريخ مسبقًا — لا يمكن إنشاء عددين لنفس اليوم.',
                    ]),
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
