<?php

namespace Sashagm\Analytics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'value', 'route', 'ip_adress'];

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}