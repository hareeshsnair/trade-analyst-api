<?php

namespace App\Traits;

trait WeekDays
{
    private function getPreviousDate($filter, $prev = null)
    {
        return $this;
        switch($filter)
        {
            case 'week': return $this->subweeks($pre)->subDay(2);
            case 'month': return $this->subMonths($prev);
            case 'quarter': return $this->subQuarters($prev);
            case 'year': return $this->subYears($prev);
            default: return $this;
        }
    }
}