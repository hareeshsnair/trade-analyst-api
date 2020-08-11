<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\SerializeDate;

class OrderResource extends JsonResource
{
    use SerializeDate;

    protected $casts = [
        'created_at' => 'datetime:Y-m',
    ];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "stock" => $this->stock,
            "exchange_id" => $this->exchange_id,
            "instrument" => $this->instrument,
            "trade_type" => $this->tradeType,
            "price" => $this->price,
            "qty" => $this->qty,
            "is_mtf_opted" => $this->is_mtf_opted,
            "net_value" => $this->net_value,
            "pnl" => $this->pnl,
            "pnl_percentage" => $this->pnl_percentage,
            "net_pnl" => $this->net_pnl,
            "brokerage" => $this->brokerage,
            "tax" => $this->tax,
            "net_amount" => $this->net_amount,
            "trade_on" => $this->trade_on,
            'portfolio' => $this->portfolio,
            "created_at" => $this->serializeTimezone($this->created_at),
            "updated_at" => $this->serializeTimezone($this->updated_at)
        ];
    }
}
