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
       'order_id','name_surname','address','city_id','phone','invoice','tckn','vergi_no'

    ];

    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }
}
