<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('الاسم')
                ->required(),

            TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->revealable()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn ($state): bool => filled($state))
                ->helperText('عند التعديل: اتركها فارغة للإبقاء على كلمة المرور الحالية.'),

            Select::make('role')
                ->label('الدور (الصلاحية)')
                ->options(collect(UserRole::cases())->mapWithKeys(fn (UserRole $r) => [$r->value => $r->label()]))
                ->default(UserRole::Editor->value)
                ->required()
                ->helperText('مدير: كل الصلاحيات وإدارة الأعضاء · محرّر: تجهيز الأخبار والتوصيات · ناشر: التوليد والتحميل.'),
        ]);
    }
}
