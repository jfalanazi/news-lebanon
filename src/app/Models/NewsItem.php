<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsItem extends Model
{
    protected $fillable = [
        'edition_id', 'category', 'url', 'source_name',
        'title', 'excerpt', 'body', 'priority', 'position', 'ai_generated', 'active',
    ];

    protected $casts = ['ai_generated' => 'boolean', 'active' => 'boolean'];

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }

    // اشتقاق اسم المصدر تلقائيًا من الرابط عند الحفظ إن لم يُحدَّد
    protected static function booted(): void
    {
        static::saving(function (NewsItem $item) {
            if (empty($item->source_name) && ! empty($item->url)) {
                $item->source_name = static::deriveSource($item->url);
            }
        });
    }

    public static function deriveSource(string $url): ?string
    {
        $map = [
            'aljazeera' => 'الجزيرة', 'annahar' => 'النهار', 'al-akhbar' => 'الأخبار',
            'lbcgroup' => 'LBCI', 'lbci' => 'LBCI', 'mtv' => 'MTV', 'aljadeed' => 'الجديد',
            'nna-leb' => 'الوكالة الوطنية', 'reuters' => 'رويترز', 'aawsat' => 'الشرق الأوسط',
            'bbc' => 'BBC', 'skynewsarabia' => 'سكاي نيوز عربية', 'alarabiya' => 'العربية',
            'almanar' => 'المنار', 'elnashra' => 'النشرة', 'naharnet' => 'Naharnet',
        ];
        $host = parse_url(str_starts_with($url, 'http') ? $url : "https://$url", PHP_URL_HOST) ?? '';
        $host = preg_replace('/^www\./', '', $host);
        foreach ($map as $key => $name) {
            if (str_contains($host, $key)) return $name;
        }
        $parts = explode('.', $host);
        $base  = $parts[count($parts) - 2] ?? $host;
        return $base ? ucfirst($base) : null;
    }
}
