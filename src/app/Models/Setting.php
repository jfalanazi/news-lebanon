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

    // المدن اللبنانية المتاحة: إحداثيات الطقس + اسم مدينة الصلاة في aladhan
    public static function cities(): array
    {
        return [
            'بيروت'   => ['lat' => 33.8938, 'lng' => 35.5018, 'aladhan' => 'Beirut'],
            'طرابلس'  => ['lat' => 34.4333, 'lng' => 35.8333, 'aladhan' => 'Tripoli'],
            'صيدا'    => ['lat' => 33.5606, 'lng' => 35.3758, 'aladhan' => 'Sidon'],
            'صور'     => ['lat' => 33.2705, 'lng' => 35.1938, 'aladhan' => 'Tyre'],
            'زحلة'    => ['lat' => 33.8463, 'lng' => 35.9019, 'aladhan' => 'Zahle'],
            'جونية'   => ['lat' => 33.9808, 'lng' => 35.6178, 'aladhan' => 'Jounieh'],
            'بعلبك'   => ['lat' => 34.0058, 'lng' => 36.2181, 'aladhan' => 'Baalbek'],
            'النبطية' => ['lat' => 33.3789, 'lng' => 35.4839, 'aladhan' => 'Nabatieh'],
        ];
    }

    // بيانات المدينة المختارة حاليًا (افتراضيًا بيروت)
    public static function cityData(): array
    {
        $cities = static::cities();
        $current = static::get('city', 'بيروت');

        return $cities[$current] ?? $cities['بيروت'];
    }

    // اسم عربي مفهوم لكل إعداد
    public static function labelFor(string $key): string
    {
        return [
            'city'                => 'مدينة الطقس ومواقيت الصلاة',
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
