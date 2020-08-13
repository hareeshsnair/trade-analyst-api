<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

trait OrderParams
{
    private $request;

    private $avgPrice;
    private $netValue;
    private $qty;
    private $sAvgPrice;
    private $sNetValue;
    private $sQty;

    public function init(Request $request)
    {
        $this->request = $request;
    }

    public function getParamsOnType()
    {
        if($this->request->order_type === 'b') {

            $this->avgPrice = 'avg_buy_price';
            $this->netValue = 'net_buy_value';
            $this->qty = 'buy_qty';
            $this->sAvgPrice = 'avg_sell_price';
            $this->sNetValue = 'net_sell_value';
            $this->sQty = 'sell_qty';
        }
        else {

            $this->sAvgPrice = 'avg_buy_price';
            $this->sNetValue = 'net_buy_value';
            $this->sQty = 'buy_qty';
            $this->avgPrice = 'avg_sell_price';
            $this->netValue = 'net_sell_value';
            $this->qty = 'sell_qty';
        }

        return $this;
    }

    public function validateShortSell()
    {
        $con = config('constants');
        
        if($this->request->trade_type_id == $con['trades']['DELIVERY'] && 
            $this->request->order_type == $con['order_types']['SELL'] && 
            $this->request->instrument_type_id == $con['instruments']['EQ']) 
        {
            abort(422, 'Equity stocks should buy first in delivery trade');
        }
    }

    public function getFillableParams()
    {
        return $this->request->except(config('constants.order.create.except.'.$this->request->instrument_type_id));
    }

    public function getNetValue($portfolio)
    {
        $avgPrice = $this->avgPrice;
        $qty = $this->qty;

        return $portfolio->$avgPrice * $portfolio->$qty;
    }

    public function getOrderedValue($order)
    {
        return $order->price * $order->qty;
    }

    public function getNetQty($order)
    {

    }

    public function searchOrder()
    {
        return Order::where([
            'stock_id' => $this->request->stock_id, 
            'exchange_id' => $this->request->exchange_id, 
            'instrument_type_id' => $this->request->instrument_type_id, 
            'trade_type_id' => $this->request->trade_type_id,
            ])
            ->when($this->request->trade_type_id == '1', function($query) {
                return $query->where('trade_on', $this->request->trade_on);
            })
            ->when($this->request->instrument_type_id == '2', function($query) {
                return $query->where('expiry_date', $this->request->expiry_date);
            })
            ->when($this->request->instrument_type_id == '3', function($query) {
                return $query->where(['expiry_date' => $this->request->expiry_date, 'strike_price' => $this->request->strike_price, 'option_type' => $this->request->option_type]);
            })
            // ->toSql();
            ->latest()->first();
    }
}