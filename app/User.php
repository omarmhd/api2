<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table='users';
    protected $fillable = [
      'Profit_Company', 'profit_broker', 'number_deals','account_number','card','login_name', 'full_name', 'email', 'password','Role','date_work','address','phone','Commission','image','api_token','remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sold_eqaars(){

    return $this->hasMany('App\SoldEaqaar') ;

    }



    public function eqaars(){

        return $this->hasMany('App\Eaqaar') ;

        }


        public function AauthAcessToken(){
            return $this->hasMany('\App\OauthAccessToken');
        }
}
