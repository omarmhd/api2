<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationEaqaar extends Model
{



    protected $fillable = [
        'user_id',
        'type_id',
        'plan_id',
        'state',
        'area',
        'square',
        'Part_number',
        'space',
        'Survey_number',
        'name_seller',
        'card_seller',
        'phone_seller',
        'date_buy', 'price_buy',
        'Downpayment',
        'estimated_price'
    ];


    public  function plan()
    {

        return $this->belongsTo('App\Plan');
    }


    public  function user()
    {

        return $this->belongsTo('App\User');
    }
}
