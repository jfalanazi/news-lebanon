<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCandidate extends Model
{
    protected $fillable = ['for_date', 'category', 'priority', 'url', 'source_name', 'title', 'excerpt', 'used', 'ai_processed'];
    protected $casts = ['for_date' => 'date', 'used' => 'boolean', 'ai_processed' => 'boolean'];
}
