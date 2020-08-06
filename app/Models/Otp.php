<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public function scopeNotVerified($query) 
    {
        return $query->whereNull('verified_at');
    }
}
