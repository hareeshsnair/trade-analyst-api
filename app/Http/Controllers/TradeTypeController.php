<?php

namespace App\Http\Controllers;

use App\Models\TradeType;

class TradeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $types = TradeType::active()->get();
        return response()->success($types);
    }

}
