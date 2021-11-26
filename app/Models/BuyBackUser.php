<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyBackUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'buybacks';

    protected $fillable = [
        'user_id','name','surname','tckn','phone','email','city_id','town_id','district_id','neighborhood_id','address','iban','terms_of_use','campaigns'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function buybacks(){
        return $this->hasMany(BuyBack::class,'buyback_user_id','id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }
    public function town(){
        return $this->hasOne(Town::class,'id','town_id');
    }
    public function district(){
        return $this->hasOne(District::class,'id','district_id');
    }
    public function neighboorhood(){
        return $this->hasOne(Neighborhood::class,'id','neighborhood_id');
    }
}
