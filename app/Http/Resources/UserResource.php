<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrdersCollection;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $curMnth = $this->ordersCurMonth->sum('pnl');
        $prevMnth = $this->ordersPrevMonth->sum('pnl');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'currency' => 'Rs',
            'pnl' => [
                'prevMonth' => $prevMnth,
                'currentMonth' => $curMnth,
                'total' => $this->ordersCompleted->sum('pnl'),
                'growth' => $prevMnth ? ($curMnth - $prevMnth) / $prevMnth * 100 : $curMnth,
            ],
            // 'orders' => new OrdersCollection($this->orders)
        ];
    }
}
