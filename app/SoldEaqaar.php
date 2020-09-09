<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldEaqaar extends Model
{

    public $table='sold_eaqaars';
    protected $fillable = [
        'eaqaar_id',
        'user_id',
        'name_buyer',
        'card_buyer',
        'phone_buyer',
        'price_sell',
        'Date_sale',
        'Remaining_amount',
        'due_date',
        'Downpayment',
        'image_card',
        'profit_company',
        'Partial_condition',
        'type'
    ];

    public function user()
    {

        return $this->belongsTo('App\User');
    }

    public function eaqaar()
    {

        return $this->belongsTo('App\Eaqaar');
    }





}
