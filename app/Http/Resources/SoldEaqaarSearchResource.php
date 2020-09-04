<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldEaqaarSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[

            'state'=>$this->state,
            'area'=>$this->area,
            'square'=>$this->square,
            'Part_number'=>$this->Part_number,
            'space'=>$this->space,
            'Survey_number'=>$this->Survey_number,
            'name_buyer'=>$this->soldeaqaar->name_buyer,
            'card_buyer'=>$this->soldeaqaar->card_buyer,
            'phone_buyer'=>$this->soldeaqaar->phone_buyer,
            'price_sell'=>$this->soldeaqaar->price_sell,
            'Date_sale'=>$this->soldeaqaar->Date_sale,
            'Remaining_amount'=>$this->soldeaqaar->Remaining_amount,
            'Downpayment'=>$this->soldeaqaar->Downpayment,
            'Partial_condition'=>$this->soldeaqaar->Partial_condition,
            'due_date'=>$this->soldeaqaar->due_date,
            'image_card'=>$this->soldeaqaar->image_card,

        ];
    }
}
