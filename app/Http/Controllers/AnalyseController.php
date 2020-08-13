<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AnalyseController extends Controller
{
    public function getPnl(Request $request)
    {
        return response()->success(Order::mine()->pnl()->mergeByDay()->get());
    }
}
