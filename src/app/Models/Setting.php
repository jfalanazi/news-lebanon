<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // اسم عربي مفهوم لكل إعداد
    public static function labelFor(string $key): string
    {
        return [
            'issue_anchor_number' => 'رقم مرساة العدد (نظام ترقيم قديم)',
            'issue_anchor_date'   => 'تاريخ مرساة العدد (نظام ترقيم قديم)',
            'prayer_method'       => 'طريقة حساب مواقيت الصلاة',
            'prayer_fajr_angle'   => 'زاوية الفجر (درجات)',
            'prayer_isha_angle'   => 'زاوية العشاء (درجات)',
            'weather_lat'         => 'خط عرض موقع الطقس (بيروت)',
            'weather_lng'         => 'خط طول موقع الطقس (بيروت)',
            'default_quote'       => 'عبارة التذييل الافتراضية',
        ][$key] ?? $key;
    }
}
