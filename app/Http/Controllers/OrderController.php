<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Portfolio;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
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
        // $order = Order::create($request->all());
// print_r($request->trade_on);exit;
        $ordered = Order::where([
            'stock_id' => $request->stock_id, 
            'exchange_id' => $request->exchange_id, 
            'instrument_type_id' => $request->instrument_type_id, 
            'trade_type_id' => $request->trade_type_id,
            ])
            ->when($request->trade_type_id == '1', function($query) use($request) {
                return $query->where('trade_on', $request->trade_on);
            })
            ->when($request->instrument_type_id == '2', function($query) use($request) {
                return $query->where('expiry_date', $request->expiry_date);
            })
            ->when($request->instrument_type_id == '3', function($query) use($request) {
                return $query->where(['expiry_date' => $request->expiry_date, 'strike_price' => $request->strike_price, 'option_type' => $request->option_type]);
            })
            // ->toSql();
            ->latest()->first();
// print_r($ordered);exit;
        if(!$ordered)
        {
            // First time order on a stock
            DB::transaction(function () use ($request) {
                $order = Order::create($request->all());
                $portfolio = new Portfolio();
                $portfolio->user_id = $order->user_id;
                $avgPrice = $request->order_type === 'b' ? 'avg_buy_price' : 'avg_sell_price';
                $netValue = $request->order_type === 'b' ? 'net_buy_value' : 'net_sell_value';
                $qty = $request->order_type === 'b' ? 'buy_qty' : 'sell_qty';
                // $netQty = $request->order_type === 'b' ? 'net_buy_value' : 'net_sell_value';
                $portfolio->$avgPrice = $order->price;
                $portfolio->$qty = $order->qty;
                $portfolio->$netValue = $order->price * $order->qty;
                $portfolio->save();

                $order->portfolio_id = $portfolio->id;
                $order->save();
            }, 2);
             
        }
        else {
            // echo (int)4;exit;
            // print_r($ordered->order_type);exit;
            // if($ordered->order_type === $request->order_type)
            // {
                $portfolio = Portfolio::find($ordered->portfolio_id);
                // print_r();exit;
                if($portfolio->status)
                {
                    
                    // echo (int)$portfolio->$sQty;exit;
                    // return;
                    // If there is any active portfolio available
                    DB::transaction(function () use ($request, $portfolio) {
                        $sAvgPrice = $request->order_type === 's' ? 'avg_buy_price' : 'avg_sell_price';
                    $sNetValue = $request->order_type === 's' ? 'net_buy_value' : 'net_sell_value';
                    $sQty = $request->order_type === 's' ? 'buy_qty' : 'sell_qty';

                        $order = Order::create($request->all());
                        // $portfolio = Portfolio::find($ordered->portfolio_id);
                        $portfolio->user_id = $order->user_id;
                        $avgPrice = $request->order_type === 'b' ? 'avg_buy_price' : 'avg_sell_price';
                        $netValue = $request->order_type === 'b' ? 'net_buy_value' : 'net_sell_value';
                        $qty = $request->order_type === 'b' ? 'buy_qty' : 'sell_qty';
                        
                        // $availableQty = (int)$portfolio->$sQty - $portfolio->$qty;
                        
                        $portfolio->$avgPrice = (($portfolio->$avgPrice * $portfolio->$qty) + ($order->price * $order->qty)) / ($portfolio->$qty+$order->qty);
                        $portfolio->$qty = $portfolio->$qty + $order->qty;
                        $portfolio->$netValue = $portfolio->$avgPrice * $portfolio->$qty;

                        if($portfolio->$sAvgPrice)
                        {
                            if($request->order_type === 's') {
                                $portfolio->net_pnl =  ($portfolio->$avgPrice - $portfolio->$sAvgPrice) * $portfolio->$qty;
                                if($portfolio->$sQty > $portfolio->$qty)
                                    $order->pnl = ($request->price - $portfolio->$sAvgPrice) * $request->qty;
                            }
                            if($request->order_type === 'b') {
                                // echo $portfolio->$sQty."==".$portfolio->$qty;exit;return false;
                                $portfolio->net_pnl =  ($portfolio->$sAvgPrice - $portfolio->$avgPrice) * $portfolio->$qty;
                                if($portfolio->$sQty > $portfolio->$qty)
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
                // }
                // else {
                //     DB::transaction(function () use ($request) {
                //         $order = Order::create($request->all());
                //         $portfolio = new Portfolio();
                //         $portfolio->user_id = $order->user_id;
                //         $avgPrice = $request->order_type === 'b' ? 'avg_buy_price' : 'avg_sell_price';
                //         $netValue = $request->order_type === 'b' ? 'net_buy_value' : 'net_sell_value';
                //         $qty = $request->order_type === 'b' ? 'buy_qty' : 'sell_qty';
                //         // $netQty = $request->order_type === 'b' ? 'net_buy_value' : 'net_sell_value';
                //         $portfolio->$avgPrice = $order->price;
                //         $portfolio->$qty = $order->qty;
                //         $portfolio->$netValue = $order->price * $order->qty;
                //         $portfolio->save();
        
                //         $order->portfolio_id = $portfolio->id;
                //         $order->save();
                //     }, 2);
                // }
                
            }
        }

        // print_r($ordered->isEmpty());exit;

        // $portfolio = new Portfolio();
        // $portfolio->avg_buy_price = $order->price;
        // $portfolio->avg_buy_qty = $order->qty;
        // $portfolio->save();

        // $order->portfolio_id = $portfolio->id;
        // $order->save();
        // echo $order->created_at;exit;
        // return response($order->created_at, 200);
        return response()->success('created');
    }

    protected function getOrderSpec()
    {

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
