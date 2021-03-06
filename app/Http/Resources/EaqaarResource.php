<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EaqaarResource extends JsonResource
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
            'status'=>'success',
              'data'=>[

             'id'=>$this->id,
             'state'=>$this->state,
             'status_aqqaar'=>$this->status,
             'area'=>$this->area,
             'square'=>$this->square,
            'Part_number'=>$this->Part_number,
            'space'=>$this->space,
             'Survey_number'=>$this->Survey_number,
            'name_seller'=>$this->name_seller,
            'card_seller'=>$this->card_seller,
            'phone_seller'=>$this->phone_seller,
            'date_buy'=>$this->date_buy,
            'price_buy'=>$this->price_buy,
            'Downpayment'=>$this->Downpayment,
            'estimated_price'=>$this->estimated_price,
            'Remaining_amount'=>$this->Remaining_amount,
            'details'=>$this->detials,
            'image'=>$this->image,
            'image_card'=>$this->image_card,
            'url'=>$this->url,
            'use'=>$this->use,
            'user'=>$this->user,
            'type'=>$this->plan->type,
            'plan'=>$this->plan,]
            ];
    }
}
