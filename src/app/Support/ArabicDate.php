<?php

namespace App\Support;

use Carbon\Carbon;

/** تنسيق تاريخ عربي موحّد بالأشهر الشامية — المرجع الوحيد لعرض التواريخ في اللوحة والصفحات */
class ArabicDate
{
    private const DOW = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

    private const LEV = ['كانون الثاني', 'شباط', 'آذار', 'نيسان', 'أيار', 'حزيران', 'تموز', 'آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'];

    /** «الأربعاء 29 تموز 2026» */
    public static function full($date): string
    {
        $d = Carbon::parse($date);

        return self::DOW[$d->dayOfWeek] . ' ' . $d->day . ' ' . self::LEV[$d->month - 1] . ' ' . $d->year;
    }

    /** «29 تموز 2026» */
    public static function short($date): string
    {
        $d = Carbon::parse($date);

        return $d->day . ' ' . self::LEV[$d->month - 1] . ' ' . $d->year;
    }

    /** «تموز 2026» */
    public static function monthYear($date): string
    {
        $d = Carbon::parse($date);

        return self::LEV[$d->month - 1] . ' ' . $d->year;
    }
}
