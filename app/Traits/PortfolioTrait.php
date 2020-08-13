<?php

namespace App\Traits;

use App\Traits\OrderParams;
use App\Models\Order;
use App\Models\Portfolio;

trait PortfolioTrait
{
    use OrderParams;

    protected function createNewPortfolio(Order $order)
    {
        $portfolio = new Portfolio();
        $params = $this->getParamsOnType();

        $avgPrice = $params->avgPrice;
        $netValue = $params->netValue;
        $qty = $params->qty;
        
        $portfolio->user_id = $order->user_id;
        $portfolio->$avgPrice = $order->price;
        $portfolio->$qty = $order->qty;
        $portfolio->$netValue = $order->price * $order->qty;

        $portfolio->save();

        $order->portfolio_id = $portfolio->id;
        $order->save();
    }

    public function updatePortfolio(Portfolio $portfolio, Order $order)
    {
        $params = $this->getParamsOnType();

        $avgPrice = $params->avgPrice;
        $netValue = $params->netValue;
        $qty = $params->qty;

        $sAvgPrice = $params->sAvgPrice;
        $sNetValue = $params->sNetValue;
        $sQty = $params->sQty;
        
        $portfolio->user_id = $order->user_id;
        $portfolio->$avgPrice = (($this->getNetValue($portfolio)) + ($this->getOrderedValue($order))) / ($portfolio->$qty+$order->qty);
        $portfolio->$qty = $portfolio->$qty + $order->qty;
        $portfolio->$netValue = $this->getNetValue($portfolio);

        if($portfolio->$sAvgPrice)
        {
            if($portfolio->$sQty < $portfolio->$qty)
                abort(422, 'There is no sufficient quantity to squareoff');

            if($this->request->order_type === 's') {
                $portfolio->net_pnl =  ($portfolio->$avgPrice - $portfolio->$sAvgPrice) * $portfolio->$qty;
                if($portfolio->$sQty >= $portfolio->$qty)
                    $order->pnl = ($this->request->price - $portfolio->$sAvgPrice) * $this->request->qty;
            }
            if($this->request->order_type === 'b') {
                $portfolio->net_pnl =  ($portfolio->$sAvgPrice - $portfolio->$avgPrice) * $portfolio->$qty;
                if($portfolio->$sQty >= $portfolio->$qty)
                    $order->pnl = ($portfolio->$sAvgPrice - $this->request->price) * $this->request->qty;
            }
            if($portfolio->buy_qty === $portfolio->sell_qty) {
                $portfolio->status = 0;
            }
        }

        $portfolio->save();

        $order->portfolio_id = $portfolio->id;
        $order->save();
    }
}