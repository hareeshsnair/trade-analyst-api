<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile',
    ];

    public function scopeVerified($query) 
    {
        return $query->where('is_mobile_verified', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function trades()
    {
        return $this->hasMany('App\Models\TradeHistory', 'user_id', 'id');
    }

    public function otp()
    {
        return $this->hasOne('App\Models\Otp')->latest();
    }

}
