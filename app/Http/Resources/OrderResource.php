<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
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
            "price" => null,
            "qty" => null,
            "is_mtf_opted" => 0,
            "net_value" => null,
            "pnl" => null,
            "pnl_percentage" => null,
            "net_pnl" => null,
            "brokerage" => null,
            "tax" => null,
            "net_amount" => null,
            "bought_on" => null,
            "sold_on" => null,
            "created_at" => null,
            "updated_at" => null
        ];
    }
}
