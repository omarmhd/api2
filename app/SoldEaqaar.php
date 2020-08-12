<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldEaqaar extends Model
{
    protected $fillable = [
        'eaqaar_id',
        'user_id',
        'name_buyer',
        'card_buyer',
        'phone_buyer',
        'price_buy',
        'Date_sale',
        'Remaining_amount',
        'due_date',
        'Downpayment'
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
