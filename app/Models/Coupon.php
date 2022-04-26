<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'coupons';

    protected $fillable = [
        'code','amount','percentage','is_active','usage','expires_at'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function orders(){
        return $this->hasMany(Order::class,'coupon_id','id');
    }

}
