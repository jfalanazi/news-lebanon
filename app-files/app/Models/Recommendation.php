<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    protected $fillable = ['edition_id', 'type', 'name', 'description', 'area', 'position'];

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }
}
