<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Date;
// use Illuminate\Support\DateFactory;
use App\Models\Order;
use App\Traits\WeekDays;
use Carbon\Carbon;
// use Carbon\CarbonImmutable;
use App\Services\CurrentDaysCarbonMixin;

class AnalyseController extends Controller
{
    public function __construct()
    {
        // DateFactory::use(CarbonImmutable::class);
    }
    // use WeekDays;
    public function getPnl(Request $request)
    {
        

        $filterBy = $request->filter_by ?? 'week';
        $prev = $request->prev ?? 0;


        
        // for ($i=0; $i < 5; $i++) { 
        //     echo $period[$i]->format('Y-m-d');
        // }
        // exit;return;
        
        // return $period->toArray();
        
        // $isWeekday = Carbon::now()->isWeekday();
        $today = Carbon::today();
        $format = 'd M';
// $today->subweeks(3);
// return $today->format('Y-m-d');
        // Carbon::mixin(WeekDays::class);
        // $date = Carbon::parse('First saturday of December 2018');
        // return $date->getPreviousDate($filterBy, $prev);
        // return Carbon::hasMacro('getPreviousDate');
        // Carbon::mixin(new CurrentDaysCarbonMixin());
        // $today = Date::today();
        // return Carbon::getCurrentWeekDays();//->format('Y-m-d');

        Carbon::mixin(new CurrentDaysCarbonMixin());
// return Carbon::now()->addWeek(0);
        if($filterBy == 'week') {
            $start = Carbon::now()->subweeks($prev)->weekStart();
            $end = Carbon::now()->subweeks($prev)->weekEnd();
            $raw = DB::raw('DAY(trade_on)');
        }
        if($filterBy == 'month') {
            $start = Carbon::now()->subMonths($prev)->startOfMonth();
            $end = Carbon::now()->subMonths($prev)->endOfMonth();
            $raw = DB::raw('WEEK(trade_on)');
        }
        if($filterBy == 'quarter') {
            $start = Carbon::now()->subQuarters($prev)->startOfQuarter();
            $end = Carbon::now()->subQuarters($prev)->endOfQuarter();
            $raw = DB::raw('MONTH(trade_on)');
            $format = 'M Y';
        }
        if($filterBy == 'year') {
            $start = Carbon::now()->subYears($prev)->startOfYear();
            $end = Carbon::now()->subYears($prev)->endOfYear();
            $raw = DB::raw('QUARTER(trade_on)');
            $format = 'M Y';
        }
        // Carbon::mixin(new CurrentDaysCarbonMixin());
        // return response()->success(Carbon::getCurrentWeekDays('s'));
        
        // return ['today' => $today->format('Y-m-d'), 'this week'=>['start'=>$start, 'end'=>$end]];
        
        $period = Carbon::parse($start)->daysUntil($end);
        $days = [];
        foreach ($period as $key => $value) {
            $days[] = $value->format('Y-m-d');
        }
        // exit;
// return $days;

        $trades = Order::mine()->pnl()
                    ->whereBetween('trade_on',[$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->groupBy($raw)
                    ->selectRaw('trade_on, sum(pnl) as net_pnl')->get();
        $data = [
            'days' => $days,
            'trades' => $trades, 
            'today' => $today->format('d M Y'),
            'from' => $start->format($format),
            'to' => $end->format($format),
        ];

        return response()->success($data);
    }
}
