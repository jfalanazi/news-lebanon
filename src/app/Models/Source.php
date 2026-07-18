<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['name', 'domain', 'url', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
