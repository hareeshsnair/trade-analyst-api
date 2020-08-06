<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstrumentType extends Model
{
    // Equity, FnO, Commodities etc
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
