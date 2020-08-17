<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DbQueries
{
    public function getAnalyseQry(Request $request, $date)
    {
        $filterBy = $request->filter_by ?? 'week';
        $prev = $request->prev ?? 0;

        if($filterBy == 'week') {
            $start = $this->getPreviousDate($date, $filterBy, $prev);//subweeks($prev)->startOfWeek(Carbon::MONDAY);
            // $end = $now->subweeks($prev)->subDay(2)->endOfWeek(Carbon::FRIDAY);
        }
        // if($filterBy == 'month') {
        //     $start = Carbon::now()->subMonths($prev)->startOfMonth();
        //     $end = Carbon::now()->subMonths($prev)->endOfMonth();
        // }
        // if($filterBy == 'quarter') {
        //     $start = Carbon::now()->subQuarters($prev)->startOfQuarter();
        //     $end = Carbon::now()->subQuarters($prev)->endOfQuarter();
        // }
        // if($filterBy == 'year') {
        //     $start = Carbon::now()->subYears($prev)->startOfYear();
        //     $end = Carbon::now()->subYears($prev)->endOfYear();
        // }
    }

    private function getPreviousDate($date, $filter, $prev = null)
    {print_r($this);exit;
        switch($filter)
        {
            case 'week': return $date->subweeks($pre)->subDay(2);
            case 'month': return $date->subMonths($prev);
            case 'quarter': return $date->subQuarters($prev);
            case 'year': return $date->subYears($prev);
            default: return $date;
        }
    }

    private function getStartingDate($date, $filter, $custom = null)
    {
        switch($filter)
        {
            case 'week': return $date->startOfWeek($custom);
            case 'month': return $date->startOfMonth($custom);
            case 'quarter': return $date->startOfQuarter($custom);
            case 'year': return $date->startOfYear($custom);
            default: return $date;
        }
    }

    private function getEndingDate($date, $filter, $custom = null)
    {
        switch($filter)
        {
            case 'week': return $date->endOfWeek($custom);
            case 'month': return $date->endOfMonth($custom);
            case 'quarter': return $date->endOfQuarter($custom);
            case 'year': return $date->endOfYear($custom);
            default: return $date;
        }
    }
}