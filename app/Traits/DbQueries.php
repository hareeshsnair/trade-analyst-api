<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait DbQueries
{
    public function pnlGroupBy($filter)
    {
        switch($filter)
        {
            case 'week': return DB::raw('DAY(trade_on)');
            case 'month': return DB::raw('WEEK(trade_on)');
            case 'quarter': return DB::raw('MONTH(trade_on)');
            case 'year': return DB::raw('QUARTER(trade_on)');
            default: return null;
        }
    }

    public function pnlSelectRaw($filter)
    {
        switch($filter)
        {
            case 'week': return DB::raw('DAY(trade_on) as day');
            case 'month': return DB::raw('WEEK(trade_on)+1 as week');
            case 'quarter': return DB::raw('MONTH(trade_on) as month');
            case 'year': return DB::raw('QUARTER(trade_on) as quarter');
            default: return null;
        }
    }
}