<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'guests';

    protected $fillable = [
       'ip','guid','expires_at'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];


    public function cart_items(){
        //return $this->hasManyThrough(CartItem::class,CartOrderPivot::class,'order_id','id');
        return $this->hasMany(GuestCartItem::class,'guid','id');
    }

}
