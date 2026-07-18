<?php
namespace App\Enums;

// أدوار النظام الثلاثة
enum UserRole: string
{
    case Admin     = 'admin';      // مدير — كل الصلاحيات + إدارة المستخدمين والإعدادات
    case Editor    = 'editor';     // محرّر — يجهّز الأخبار/التوصيات/الفعاليات ويعلّم العدد جاهزًا
    case Publisher = 'publisher';  // ناشر — يرى الأعداد الجاهزة ويولّد الصورة ويحمّلها

    public function label(): string
    {
        return match ($this) {
            self::Admin     => 'مدير',
            self::Editor    => 'محرّر',
            self::Publisher => 'ناشر',
        };
    }
}
