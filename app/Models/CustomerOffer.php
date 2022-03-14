<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOffer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_offers';

    protected $fillable = [
        'customer_id','model_id','answers','discount','price_offer','date'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function model(){
        return $this->hasOne(ProductModel::class,'id','model_id');
    }
}
