<?php
namespace App\Filament\Resources\Editions\Schemas;

use App\Models\Edition;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('preview')
                    ->label('معاينة النشرة')
                    ->columnSpanFull()
                    ->content(function ($record) {
                        if (! $record) {
                            return new HtmlString('<div style="color:#66705F;font-size:13px">ستظهر المعاينة هنا بعد الضغط على «توليد الصورة».</div>');
                        }
                        $path = storage_path('app/public/newsletters/edition-' . $record->issue_number . '.png');
                        if (! file_exists($path)) {
                            return new HtmlString('<div style="color:#66705F;font-size:13px">لم تُولّد صورة لهذا العدد بعد. اضغط «توليد الصورة» أعلى الصفحة.</div>');
                        }
                        $ts = filemtime($path);
                        $date = Carbon::parse($record->edition_date);
                        $caption = "🗞️ نشرة لبنان — العدد {$record->issue_number}\n"
                            . $date->translatedFormat('l') . ' · ' . $date->format('Y/m/d')
                            . ($record->caption_link ? "\n🔗 " . $record->caption_link : '');

                        return new HtmlString(view('filament.edition-preview', [
                            'issue'   => $record->issue_number,
                            'ts'      => $ts,
                            'caption' => $caption,
                        ])->render());
                    }),

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
