<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders';

    protected $fillable = [
        'order_code','name_surname','cargo_company_id','cargo_company_branch_id','cargo_code','customer_id','customer_address_id','service_address_id','status','direction'



    ];

    protected $hidden = [
        'deleted_at'
    ];


    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function service_address(){
        return $this->hasOne(ServiceAddress::class,'id','customer_id');
    }

    public function cart_items(){
        return $this->hasManyThrough(CartItem::class,CartOrderPivot::class);
    }

}
