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

    // رقم العدد التلقائي = رقم آخر عدد منشور + 1 (أو الأعلى المتاح)
    public static function nextIssueNumber(): int
    {
        $lastPublished = (int) static::where('status', 'published')->max('issue_number');
        if ($lastPublished > 0) {
            return $lastPublished + 1;
        }
        $maxAny = (int) static::max('issue_number');
        return $maxAny > 0 ? $maxAny + 1 : 1;
    }
}
