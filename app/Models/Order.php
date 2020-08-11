<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $fillable = [
        'user_id', 'stock_id', 'exchange_id', 'instrument_type_id', 'trade_type_id', 'expiry_date', 
        'strike_price', 'option_type', 'order_type', 'price', 'qty', 'is_mtf_opted', 'trade_on'
    ];

    public function scopeEquity($query)
    {
        return $query->where('instrument_type_id', 1);
    }

    public function scopeFutures($query)
    {
        return $query->where('instrument_type_id', 2);
    }

    public function scopeOptions($query)
    {
        return $query->where('instrument_type_id', 3);
    }

    public function scopeIntraday($query)
    {
        return $query->where('trade_type_id', 1);
    }

    public function scopeDelivery($query)
    {
        return $query->where('trade_type_id', 2);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function exchange()
    {
        return $this->belongsTo('App\Models\StockExchange');
    }

    public function stock()
    {
        return $this->belongsTo('App\Models\ListedCompany');
    }

    public function instrument()
    {
        return $this->belongsTo('App\Models\InstrumentType', 'instrument_type_id', 'id');
    }

    public function tradeType()
    {
        return $this->belongsTo('App\Models\TradeType');
    }

    public function serviceCharge()
    {
        return $this->hasOne('App\Models\TaxServiceCharge', 'trade_id', 'id');
    }

    public function portfolio()
    {
        return $this->belongsTo('App\Models\Portfolio');
    }
}
