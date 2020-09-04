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



        if($this->image_card==null){


            $img =$this->image_card;
        }else{
        $img= asset('upload_images/'.$this->image_card);
        }

        return[

            'status'=>'success',
            'sold_eqaar'=>
            ['id'=>$this->id,

            'name_buyer'=>$this->name_buyer,
            'card_buyer'=>$this->card_buyer,
            'phone_buyer'=>$this->phone_buyer,
            'price_sell'=>$this->price_sell,
            'Date_sale'=>$this->Date_sale,
            'Downpayment'=>$this->Downpayment,
            'Remaining_amount'=>$this->Remaining_amount,
            'due_date'=>$this->due_date,
            'image'=>$this->eaqaar->image ,
            'image_card'=>$img,
            'Partial_condition'=>$this->Partial_condition,
            'details'=>$this->eaqaar->detials,
            'user'=>$this->user,
            ]
        ];
    }

    public function with($request){

        return [

            'status'=>'success'


        ];


    }
}
