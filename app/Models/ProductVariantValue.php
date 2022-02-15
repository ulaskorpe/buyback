<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    use HasFactory;
    protected $table = 'product_variant_values';

    protected $fillable = [
        'product_id','variant_value_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function product(){
        return $this->hasOne(Product::class,'product_id','id');
    }

    public function value(){
        return $this->hasOne(VariantValue::class,'id','variant_value_id');
    }
}
