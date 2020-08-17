<?php

namespace App\Services;

use Carbon\Carbon;
// use Illuminate\Support\Facades\Date;
// use Illuminate\Support\DateFactory;
// use Carbon\CarbonImmutable;

class CurrentDaysCarbonMixin 
{
    // protected $start;
    // protected $end;

    function __construct() 
    {
        // DateFactory::use(CarbonImmutable::class);
    }

    public static function weekStart()
    {
        return static function() {
            return self::this()->startOfWeek(Carbon::MONDAY);
        };
    }

    public static function weekEnd()
    {
        return static function() {
            return self::this()->isWeekend() ? self::this()->subweek()->endOfWeek(Carbon::FRIDAY) : self::this()->endOfWeek(Carbon::FRIDAY);
        };
    }

    public static function getCurrentWeekDays()
    {
        return static function () { 
            // return self::this();
            // echo $a;exit;
            $startOfWeek = self::this()->startOfWeek()->format('Y-m-d');
            $weekDays = [];

            for ($i = 0; $i < 5; $i++) {
                $weekDays[] = $startOfWeek->addDays($i)->copy()->format('d');//->startOfDay()->copy();
            }

            return $weekDays;
        };
    }
}