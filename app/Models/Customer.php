<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
      'name','surname','email','password','status','ip_address','avatar','key'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function addresses(){
        return $this->hasMany(CustomerAddress::class,'customer_id','id');
    }

    public function favorites(){
        return $this->hasMany(CustomerFavorite::class,'customer_id','id');
    }
    public function first_address(){
        return $this->hasMany(CustomerAddress::class,'customer_id','id')->where('first','=',1);
    }
    public function cart_items(){
        return $this->hasMany(CartItem::class,'customer_id','id');
    }
}
