<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    public $table='expenses';
    protected $fillable = [
        'type_id','amount','details','date'
    ];



    public function type(){

        return $this->belongsTo('App\Expense_type');
    }
}
