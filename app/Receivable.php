<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{

    public $table='receivables';

    protected $fillable = ['user_name','Remaining_amount','eaqaar_id','type','date'];




    public function eaqaar()
    {

        return $this->belongsTo('App\Eaqaar');
    }
}
