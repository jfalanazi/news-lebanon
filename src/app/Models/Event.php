<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'edition_id', 'category', 'title',
        'start_date', 'end_date', 'persist_days', 'position',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }
}
