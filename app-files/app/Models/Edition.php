<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Edition extends Model
{
    protected $fillable = [
        'issue_number', 'edition_date', 'status', 'quote', 'caption_link',
        'weather', 'prayers', 'created_by', 'approved_by', 'published_at',
    ];

    protected $casts = [
        'edition_date' => 'date',
        'weather'      => 'array',
        'prayers'      => 'array',
        'published_at' => 'datetime',
    ];

    public function news(): HasMany
    {
        return $this->hasMany(NewsItem::class)->orderBy('position');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class)->orderBy('position');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('position');
    }

    // رقم العدد التلقائي بناءً على مرساة في الإعدادات
    public static function nextIssueNumber(): int
    {
        $anchorNum  = (int) (Setting::get('issue_anchor_number', 1));
        $anchorDate = Setting::get('issue_anchor_date', '2026-01-01');
        $days = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($anchorDate)->startOfDay(), false);
        return $anchorNum + max(0, $days);
    }
}
