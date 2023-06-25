<?php

namespace Sashagm\Analytics\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistic extends Model
{
    use HasFactory;


    protected $fillable = [
        'category',
        'data'

    ];

    public static function getLastWeek($category)
    {
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now();

        return self::where('category', $category)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }
}
