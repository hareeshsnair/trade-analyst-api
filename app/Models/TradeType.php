<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeType extends Model
{
    // Intraday, Delivery
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
