<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Portfolio;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrdersCollection;
use App\Traits\OrderParams;
use App\Traits\PortfolioTrait;

class OrderController extends Controller
{
    use OrderParams, PortfolioTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return response()->success(new OrdersCollection(Order::mine()->latestTrade()->latest()->get()));
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
            $this->validateShortSell();
            DB::transaction(function () {
                $order = Order::create($this->getFillableParams());
                $this->createNewPortfolio($order);
            }, 2);
        }
        else {
            $portfolio = Portfolio::find($ordered->portfolio_id);
                
            if($portfolio->status) {
                DB::transaction(function () use ($portfolio) {
                    $order = Order::create($this->getFillableParams());
                    $this->updatePortfolio($portfolio, $order);
                }, 2);  
            }
            else {
                $this->validateShortSell();
                DB::transaction(function () {
                    $order = Order::create($this->getFillableParams());
                    $this->createNewPortfolio($order);
                }, 2);
            }
        }

        return response()->success('created');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->success(new OrderResource(Order::mine()->where('id', $id)->first()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::where('id',$id)->active()->first();

        if(!$order) {
            return abort(400, 'Invalid request');
        }
        return response()->success($order->delete());
    }
}
