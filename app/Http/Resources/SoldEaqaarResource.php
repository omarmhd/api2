<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldEaqaarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return([
            'id'=>$this->id,

            'name_buyer'=>$this->name_buyer,
            'card_buyer'=>$this->card_buyer,
            'phone_buyer'=>$this->phone_buyer,
            'price_buy'=>$this->price_buy,
            'Date_sale'=>$this->Date_sale,
            'Downpayment'=>$this->Downpayment,

            'Remaining_amount'=>$this->Remaining_amount,
            'due_date'=>$this->due_date,
            'user'=>$this->user,

        ]);
    }
}
