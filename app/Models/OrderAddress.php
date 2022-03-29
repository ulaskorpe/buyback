<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddress extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'order_addresses';

    protected $fillable = [
       'customer_id','order_id','name','surname','address_1','address_2','city','zipcode','phone'

    ];


    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}
