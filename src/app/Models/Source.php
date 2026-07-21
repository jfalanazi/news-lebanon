<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['name', 'domain', 'url', 'is_active', 'last_fetched_at', 'last_fetch_count', 'last_error'];
    protected $casts = ['is_active' => 'boolean', 'last_fetched_at' => 'datetime'];
}
