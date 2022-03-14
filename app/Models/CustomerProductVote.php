<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProductVote extends Model
{
    use HasFactory;

    protected $table = 'customer_product_votes';

    protected $fillable = [
        'customer_id','product_id','vote'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
