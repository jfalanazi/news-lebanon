<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCandidate extends Model
{
    protected $fillable = ['for_date', 'category', 'url', 'source_name', 'title', 'excerpt', 'used'];
    protected $casts = ['for_date' => 'date', 'used' => 'boolean'];
}
