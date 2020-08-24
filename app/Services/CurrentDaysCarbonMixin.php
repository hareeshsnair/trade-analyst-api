<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class CurrentDaysCarbonMixin 
{
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

    public static function firstWeekdayOfMonth()
    {
        return static function() {
            return self::this()->startOfMonth()->isWeekday() ? 
                        self::this()->startOfMonth() : self::this()->startOfMonth()->nextWeekday(); 
        };
    }

    public static function lastWeekdayOfMonth()
    {
        return static function() {
            return self::this()->endOfMonth()->isWeekday() ? 
                        self::this()->endOfMonth() : self::this()->endOfMonth()->previousWeekday();
        };
    }

    public static function getParsedWeekDays()
    {
        return static function() {
            return CarbonImmutable::parse(self::this()->weekStart())->daysUntil(self::this()->weekEnd());
        };
    }

    public static function getCurrentWeekDays()
    {
        return static function () { 
            $period = self::this()->getParsedWeekDays();

            $weekDays = [];
            foreach ($period as $key => $value) {
                $weekDays[] = $value;
            }

            return $weekDays;
        };
    }

    public static function getParsedMonthWeeks()
    {
        return static function() {
            return CarbonImmutable::parse(self::this()->startOfMonth())->weeksUntil(self::this()->endOfMonth());
        };
    }

    public static function getBusinessMonthWeeks()
    {
        return static function() {
            $firstDay = self::this()->firstWeekdayOfMonth();
            $lastDay = self::this()->lastWeekdayOfMonth();
            $period = CarbonImmutable::parse($firstDay)->weeksUntil($lastDay);
            
            $weekDays = [];
            foreach ($period as $key => $value) {
                $weekDays[] = [
                    $value->weekStart()->isSameMonth($firstDay) ? $value->weekStart() : $firstDay,
                    $value->weekEnd()->isSameMonth($lastDay) ? $value->weekEnd() : $lastDay
                ];
            }
            return $weekDays;
        };
    }

    public static function getParsedQuarterMonths()
    {
        return static function() {
            return CarbonImmutable::parse(self::this()->startOfQuarter())->monthsUntil(self::this()->endOfQuarter());
        };
    }

    public static function getParsedYearQuarters()
    {
        return static function() {
            return CarbonImmutable::parse(self::this()->startOfYear())->quartersUntil(self::this()->endOfYear());
        };
    }
}