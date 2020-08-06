<?php

namespace App\Http\Controllers;

use App\Models\StockExchange;

class StockExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $exchanges = StockExchange::active()->get();
        return response()->success($exchanges);
    }

}
