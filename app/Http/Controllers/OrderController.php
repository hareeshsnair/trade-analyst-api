<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Portfolio;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Traits\OrderParams;
use App\Traits\OrderQueries;

class OrderController extends Controller
{
    use OrderParams;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $this->init($request);
        $ordered = $this->searchOrder();

        if(!$ordered)
        {
            $this->createNewPortfolio($request);
        }
        else {
            // if($ordered->order_type === $request->order_type)
            // {
                $portfolio = Portfolio::find($ordered->portfolio_id);
                // print_r();exit;
                if($portfolio->status)
                {
                    
                    // If there is any active portfolio available
                    DB::transaction(function () use ($request, $portfolio) {
                        
                        $order = Order::create($this->getFillableParams());
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
                            if($request->order_type === 's') {
                                // echo $portfolio->$sQty."==".$portfolio->$qty;exit;return false;
                                $portfolio->net_pnl =  ($portfolio->$avgPrice - $portfolio->$sAvgPrice) * $portfolio->$qty;
                                if($portfolio->$sQty >= $portfolio->$qty)
                                    $order->pnl = ($request->price - $portfolio->$sAvgPrice) * $request->qty;
                            }
                            if($request->order_type === 'b') {
                                // echo $portfolio->$sQty."==".$portfolio->$qty;exit;return false;
                                $portfolio->net_pnl =  ($portfolio->$sAvgPrice - $portfolio->$avgPrice) * $portfolio->$qty;
                                if($portfolio->$sQty >= $portfolio->$qty)
                                    $order->pnl = ($portfolio->$sAvgPrice - $request->price) * $request->qty;
                            }
                            if($portfolio->buy_qty === $portfolio->sell_qty) {
                                $portfolio->status = 0;
                            }
                        }

                        $portfolio->save();
        
                        $order->portfolio_id = $portfolio->id;
                        $order->save();
                    }, 2);
                }
                else {
                    $this->createNewPortfolio($request);
                }
                
            // }
        }

        return response()->success('created');
    }

    protected function createNewPortfolio($request)
    {
        // First time order on a stock
        $this->validateShortSell();
        DB::transaction(function () use ($request) {
            $order = Order::create($this->getFillableParams());
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
        }, 2);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
