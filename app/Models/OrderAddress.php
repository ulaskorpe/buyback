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
       'order_id','name_surname','address','city','phone','invoice'

    ];



    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}
