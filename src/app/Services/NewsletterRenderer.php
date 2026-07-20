<?php

namespace App\Services;

use App\Models\Edition;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class NewsletterRenderer
{
    // أشهر شامية/خليجية وأيام
    private array $lev = ['كانون الثاني','شباط','آذار','نيسان','أيار','حزيران','تموز','آب','أيلول','تشرين الأول','تشرين الثاني','كانون الأول'];
    private array $gulf = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
    private array $dow = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    private array $dowShort = ['أحد','إثنين','ثلاثاء','أربعاء','خميس','جمعة','سبت'];

    // يبني بيانات القالب من العدد
    public function buildData(Edition $edition): array
    {
        $date = Carbon::parse($edition->edition_date);

        $news = $edition->news->map(fn ($n) => [
            'category'    => $n->category,
            'source_name' => $n->source_name,
            'title'       => $n->title,
            'excerpt'     => $n->excerpt,
            'priority'    => $n->priority,
        ])->take(7)->values()->all();

        $recos = $edition->recommendations->map(fn ($r) => [
            'type' => $r->type,
            'name' => $r->name,
            'area' => $r->area,
        ])->take(3)->values()->all();

        $events = $edition->events->map(fn ($e) => [
            'category' => $e->category,
            'title'    => $e->title,
            'range'    => $this->eventRange($e->start_date, $e->end_date),
        ])->take(3)->values()->all();

        // الطقس والصلاة: من لقطة العدد إن وُجدت، وإلا نجلبها ونخزّنها معه (تسريع + ثبات)
        $weather = $this->validWeather($edition->weather) ? $edition->weather : $this->fetchWeather();
        $prayers = $this->validPrayers($edition->prayers) ? $edition->prayers : $this->fetchPrayers();

        $dirty = false;
        if (! $this->validWeather($edition->weather) && $this->validWeather($weather)) {
            $edition->weather = $weather;
            $dirty = true;
        }
        if (! $this->validPrayers($edition->prayers) && $this->validPrayers($prayers)) {
            $edition->prayers = $prayers;
            $dirty = true;
        }
        if ($dirty) {
            $edition->saveQuietly();
        }

        // الباركود يوجّه للصفحة العامة للعدد افتراضيًا (أو رابط مخصّص إن وُجد)
        $link = $edition->caption_link ?: url('/n/' . $edition->issue_number);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=0&data=' . urlencode($link);

        return [
            'issue'   => $edition->issue_number,
            'day'     => $this->dow[$date->dayOfWeek],
            'greg'    => $this->fmtGreg($date),
            'hijri'   => $this->fmtHijri($date),
            'news'    => $news,
            'weather' => $weather,
            'prayers' => $prayers,
            'recos'   => $recos,
            'events'  => $events,
            'quote'   => $edition->quote ?: Setting::get('default_quote', ''),
            'qrUrl'   => $qrUrl,
        ];
    }

    // يولّد الصورة ويحفظها ويعيد المسار
    public function render(Edition $edition): string
    {
        $data = $this->buildData($edition);
        $html = View::make('newsletter', $data)->render();

        $dir = storage_path('app/public/newsletters');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $path = $dir . '/edition-' . $edition->issue_number . '.png';

        Browsershot::html($html)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->windowSize(1080, 100)
            ->fullPage()
            ->waitUntilNetworkIdle()
            ->deviceScaleFactor(2)
            ->save($path);

        return $path;
    }

    private function validWeather($w): bool
    {
        return is_array($w) && isset($w["icon"], $w["cond"], $w["hi"], $w["lo"], $w["now"]) && isset($w["days"]) && is_array($w["days"]);
    }

    private function validPrayers($p): bool
    {
        return is_array($p) && isset($p["الفجر"], $p["الظهر"], $p["المغرب"]);
    }

    private function eventRange(?string $start, ?string $end): string
    {
        if (! $start) {
            return '';
        }
        $s = Carbon::parse($start);
        if (! $end) {
            return $s->day . ' ' . $this->monthPair($s->month - 1);
        }
        $e = Carbon::parse($end);
        if ($e->lte($s)) {
            return $s->day . ' ' . $this->monthPair($s->month - 1);
        }
        if ($s->month === $e->month) {
            return $s->day . '–' . $e->day . ' ' . $this->monthPair($s->month - 1);
        }
        return $s->day . ' ' . $this->lev[$s->month - 1] . ' – ' . $e->day . ' ' . $this->lev[$e->month - 1];
    }

    private function monthPair(int $i): string
    {
        return $this->lev[$i] . '/' . $this->gulf[$i];
    }

    private function fmtGreg(Carbon $d): string
    {
        return $d->day . ' ' . $this->monthPair($d->month - 1) . ' ' . $d->year;
    }

    private function fmtHijri(Carbon $d): string
    {
        // تحويل هجري تقريبي عبر IntlDateFormatter إن توفّر
        if (class_exists(\IntlDateFormatter::class)) {
            $fmt = new \IntlDateFormatter('ar_SA@calendar=islamic-umalqura', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, 'Asia/Riyadh', \IntlDateFormatter::TRADITIONAL);
            $out = $fmt->format($d->timestamp);
            return $this->toWest($out) . ' هـ';
        }
        return '';
    }

    private function toWest(string $s): string
    {
        return strtr($s, ['٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9']);
    }

    private function weatherMeta(int $code): array
    {
        if ($code === 0) return ['cond'=>'صحو','icon'=>'sun'];
        if ($code <= 2) return ['cond'=>'غائم جزئيًا','icon'=>'partly'];
        if ($code === 3) return ['cond'=>'غائم','icon'=>'cloud'];
        if ($code === 45 || $code === 48) return ['cond'=>'ضباب','icon'=>'cloud'];
        if ($code >= 51 && $code <= 67) return ['cond'=>'أمطار','icon'=>'rain'];
        if ($code >= 71 && $code <= 77) return ['cond'=>'ثلوج','icon'=>'snow'];
        if ($code >= 80 && $code <= 82) return ['cond'=>'زخات مطر','icon'=>'rain'];
        if ($code >= 95) return ['cond'=>'عواصف رعدية','icon'=>'rain'];
        return ['cond'=>'غائم جزئيًا','icon'=>'partly'];
    }

    private function fetchWeather(): array
    {
        $lat = Setting::get('weather_lat', '33.8938');
        $lng = Setting::get('weather_lng', '35.5018');
        try {
            $r = Http::timeout(15)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat, 'longitude' => $lng,
                'current' => 'temperature_2m,weather_code',
                'daily' => 'weather_code,temperature_2m_max,temperature_2m_min',
                'timezone' => 'Asia/Beirut', 'forecast_days' => 5,
            ])->json();
            $m = $this->weatherMeta($r['current']['weather_code']);
            $days = [];
            for ($i = 1; $i <= 4; $i++) {
                $dt = Carbon::parse($r['daily']['time'][$i]);
                $days[] = [
                    'd' => $this->dowShort[$dt->dayOfWeek],
                    'hi' => round($r['daily']['temperature_2m_max'][$i]),
                    'lo' => round($r['daily']['temperature_2m_min'][$i]),
                    'icon' => $this->weatherMeta($r['daily']['weather_code'][$i])['icon'],
                ];
            }
            return [
                'now' => round($r['current']['temperature_2m']),
                'hi' => round($r['daily']['temperature_2m_max'][0]),
                'lo' => round($r['daily']['temperature_2m_min'][0]),
                'cond' => $m['cond'], 'icon' => $m['icon'], 'days' => $days,
            ];
        } catch (\Throwable $e) {
            return ['now'=>24,'hi'=>27,'lo'=>19,'cond'=>'غائم جزئيًا','icon'=>'partly','days'=>[
                ['d'=>'جمعة','hi'=>28,'lo'=>20,'icon'=>'sun'],
                ['d'=>'سبت','hi'=>29,'lo'=>21,'icon'=>'partly'],
                ['d'=>'أحد','hi'=>27,'lo'=>20,'icon'=>'cloud'],
                ['d'=>'إثنين','hi'=>26,'lo'=>19,'icon'=>'rain'],
            ]];
        }
    }

    private function fetchPrayers(): array
    {
        $default = ['الفجر'=>'04:12','الشروق'=>'05:42','الظهر'=>'12:38','العصر'=>'16:18','المغرب'=>'19:34','العشاء'=>'21:04'];
        try {
            $r = Http::timeout(15)->get('https://api.aladhan.com/v1/timingsByCity', [
                'city' => 'Beirut', 'country' => 'Lebanon',
                'method' => 99, 'methodSettings' => '18,null,17.5',
            ])->json();
            $t = $r['data']['timings'];
            $hm = fn ($x) => substr($x, 0, 5);
            return [
                'الفجر' => $hm($t['Fajr']), 'الشروق' => $hm($t['Sunrise']),
                'الظهر' => $hm($t['Dhuhr']), 'العصر' => $hm($t['Asr']),
                'المغرب' => $hm($t['Maghrib']), 'العشاء' => $hm($t['Isha']),
            ];
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
