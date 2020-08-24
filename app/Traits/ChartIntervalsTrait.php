<?php

namespace App\Traits;

use App\Services\CurrentDaysCarbonMixin;
use Carbon\CarbonImmutable;

trait ChartIntervalsTrait
{
    public function getWeekIntervals($value)
    {
        return [
            'key' => $value->day,
            'value' => (string)$value->day,
        ];
    }

    public function getMonthIntervals($value)
    {
        return [
            'key' => $value[0]->week,
            'value' => $value[0]->day === $value[1]->day ? (string)$value[0]->day : $value[0]->day.'-'.$value[1]->day
        ];
    }

    public function getQuarterIntervals($value)
    {
        return [
            'key' => $value->month,
            'value' => $value->format('M'),
        ];
    }

    public function getYearIntervals($value)
    {
        return [
            'key' => $value->quarter,
            'value' => 'Q'.$value->quarter,
        ];
    }
}