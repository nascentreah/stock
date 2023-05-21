<?php

namespace App\Models;

use App\Models\Fields\EnumMarketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Market extends Model implements EnumMarketStatus
{
    /**
     * The accessors to append to the model's array form (selected fields will be automatically added to JSON).
     *
     * @var array
     */
    protected $appends = ['open'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['status','created_at','updated_at'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Custom model accessor ($market->open): Check whether market is currently open or closed
     *
     * @return bool
     */
    public function getOpenAttribute()
    {
        return self::isOpen($this->trading_start, $this->trading_end, $this->timezone_name);
    }

    /**
     * Check whether market is currently open or closed
     *
     * @param $startTime
     * @param $endTime
     * @param $timezone
     * @param int $starTimeAdjustment
     * @param int $endTimeAdjustment
     * @return bool
     */
    public static function isOpen($startTime, $endTime, $timezone, $starTimeAdjustment = 0, $endTimeAdjustment = 0)
    {
        $now = Carbon::now($timezone);
        $start = Carbon::parse($startTime, $timezone)->addMinutes($starTimeAdjustment);
        $end = Carbon::parse($endTime, $timezone)->addMinutes($endTimeAdjustment);
        return $now->isWeekday() && $now->greaterThanOrEqualTo($start) && $now->lessThanOrEqualTo($end);
    }
}
