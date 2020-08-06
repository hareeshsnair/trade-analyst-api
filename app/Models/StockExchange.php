<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockExchange extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
