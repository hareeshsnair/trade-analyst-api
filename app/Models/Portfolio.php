<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSquaredOff($query)
    {
        return $query->where('status', 0);
    }
}
