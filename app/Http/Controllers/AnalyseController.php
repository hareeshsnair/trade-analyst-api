<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrentDaysCarbonMixin;
use App\Http\Requests\GetPnlRequest;
use App\Models\Order;
use App\Traits\CarbonTrait;
use App\Traits\DbQueries;
use App\Traits\OrderParams;
use Carbon\CarbonImmutable;

class AnalyseController extends Controller
{
    use CarbonTrait, DbQueries, OrderParams;

    public function getPnl(GetPnlRequest $request)
    {
        CarbonImmutable::mixin(new CurrentDaysCarbonMixin());
        
        $filterBy = $request->filter_by ?? 'week';
        $prev = $request->prev ?? 0;
        
        $today = CarbonImmutable::today();
        
        $this->setFilter($filterBy);
        $format = $this->getdurationFormat()[$filterBy];
        
        $date = $this->getPreviousDate($today, $prev);
        $start = $this->getStartingDate($date);
        $end = $this->getEndingDate($date);
        $period = $this->getPeriod($date);

        foreach ($period as $key => $value) {
            $intervals[] = $this->getIntervals($value);
        }

        $trades = Order::mine()->pnl()
                    ->whereBetween('trade_on',[$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->groupBy($this->pnlGroupBy($filterBy))
                    ->selectRaw('trade_on, sum(pnl) as net_pnl')
                    ->addSelect($this->pnlSelectRaw($filterBy))
                    ->orderBy('trade_on', 'asc')->get();
        $data = [
            'intervals' => $intervals,
            'trade_key' => $this->getTradekey()[$filterBy],
            'trades' => $trades, 
            'today' => [
                'date' => $today->format('d M Y'),
                'week' => $today->week,
                'month' => $today->month,
            ],
            'from' => $start->format($format),
            'to' => $end->format($format),
        ];

        return response()->success($data);
    }
}
