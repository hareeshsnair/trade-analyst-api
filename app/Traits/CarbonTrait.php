<?php

namespace App\Traits;

use App\Services\CurrentDaysCarbonMixin;

trait CarbonTrait
{
    protected $filter;

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function getPreviousDate($date, $prev = 0)
    {
        switch($this->filter)
        {
            case 'week': return $date->subweeks($prev);
            case 'month': return $date->subMonths($prev);
            case 'quarter': return $date->subQuarters($prev);
            case 'year': return $date->subYears($prev);
            default: return $date;
        }
    }

    public function getStartingDate($date)
    {
        switch($this->filter)
        {
            case 'week': return $date->weekStart();
            case 'month': return $date->startOfMonth();
            case 'quarter': return $date->startOfQuarter();
            case 'year': return $date->startOfYear();
            default: return $date;
        }
    }

    public function getEndingDate($date)
    {
        switch($this->filter)
        {
            case 'week': return $date->weekEnd();
            case 'month': return $date->endOfMonth();
            case 'quarter': return $date->endOfQuarter();
            case 'year': return $date->endOfYear();
            default: return $date;
        }
    }

    public function getPeriod($date)
    {
        switch($this->filter)
        {
            case 'week': return $date->getCurrentWeekDays();
            case 'month': return $date->getBusinessMonthWeeks();
            case 'quarter': return $date->getParsedQuarterMonths();
            case 'year': return $date->getParsedYearQuarters();
            default: return $date;
        }
    }
}