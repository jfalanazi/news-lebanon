<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'issue_anchor_number' => '1',            // أول عدد رقمه
            'issue_anchor_date'   => '2026-01-01',   // بتاريخ
            'prayer_method'       => 'darfatwa',     // تقريبي عبر Aladhan
            'prayer_fajr_angle'   => '18',
            'prayer_isha_angle'   => '17.5',
            'weather_lat'         => '33.8938',
            'weather_lng'         => '35.5018',
            'default_quote'       => 'بيروت مدينةٌ تُولد من رمادها كل صباح.',
        ];
        foreach ($defaults as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }
    }
}
