<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense_type extends Model
{

    public $table='Expense_types';
    protected $fillable = [
        'name'
    ];

}
