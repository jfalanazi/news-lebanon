<?php
namespace App\Filament\Resources\Editions\Schemas;

use App\Models\Edition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                // يمين (البداية): بيانات العدد — مطوية عند التحرير ليتصدّر المحتوى والمعاينة
                Section::make('بيانات العدد')
                    ->description('تُملأ تلقائيًا — عدّلها عند الحاجة.')
                    ->columnSpan(1)
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(fn (string $operation): bool => $operation === 'edit')
                    ->schema([
                        TextInput::make('issue_number')
                            ->label('رقم العدد')
                            ->required()
                            ->numeric()
                            ->default(fn () => Edition::nextIssueNumber())
                            ->readOnly()
                            ->helperText('يُحدَّد تلقائيًا بتسلسل الأعداد — الرقم يتبع الزمن.')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'رقم العدد مستخدم مسبقًا — اختر رقمًا آخر.',
                            ]),
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
                                'published' => 'منشور',
                            ])
                            ->default('draft')
                            ->required(),
                        TextInput::make('quote')
                            ->label('عبارة التذييل'),
                        TextInput::make('caption_link')
                            ->label('رابط التعليق / QR')
                            ->url(),
                    ]),

                // يسار (النهاية): المعاينة الحيّة
                Placeholder::make('preview')
                    ->label('معاينة النشرة')
                    ->columnSpan(1)
                    ->content(function ($record) {
                        if (! $record) {
                            return new HtmlString('<div style="color:#66705F;font-size:13px">احفظ العدد أولًا لتظهر المعاينة الحيّة.</div>');
                        }
                        $path = storage_path('app/public/newsletters/edition-' . $record->issue_number . '.png');
                        $hasImage = file_exists($path);
                        $ts = $hasImage ? filemtime($path) : 0;

                        return new HtmlString(view('filament.edition-preview', [
                            'editionId' => $record->id,
                            'issue'     => $record->issue_number,
                            'ts'        => $ts,
                            'hasImage'  => $hasImage,
                        ])->render());
                    }),
            ]);
    }
}
