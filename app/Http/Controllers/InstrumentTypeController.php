<?php

namespace App\Http\Controllers;

use App\Models\InstrumentType;

class InstrumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $instruments = InstrumentType::active()->get();
        return response()->success($instruments);
    }

}
