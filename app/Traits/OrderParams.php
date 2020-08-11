<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait OrderParams
{
    protected $avgPrice;
    protected $netValue;
    protected $qty;
    protected $sAvgPrice;
    protected $sNetValue;
    protected $sQty;

    public function getParamsOnType(Request $request)
    {
        if($request->order_type === 'b') {

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
}