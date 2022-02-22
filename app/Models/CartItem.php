<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cart_items';

    protected $fillable = [
       'item_code', 'customer_id','product_id','memory_id','color_id','order_id','status','quantity','price'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function memory(){
        return $this->hasOne(Memory::class,'id','memory_id');
    }

    public function color(){
        return $this->hasOne(Color::class,'id','color_id');
    }

    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}
