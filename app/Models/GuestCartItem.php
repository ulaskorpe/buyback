<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GuestCartItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'guest_cart_items';

    protected $fillable = [
        'item_code', 'guid','product_id','memory_id','color_id','quantity','price'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function guest(){
        return $this->hasOne(Guest::class,'id','guid');
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
}