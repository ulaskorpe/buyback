<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartOrderPivot extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cart_order_pivot';

    protected $fillable = [
        'cart_item_id','order_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function cart_item(){
        return $this->hasOne(CartItem::class,'id','cart_item_id');
    }
    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}
