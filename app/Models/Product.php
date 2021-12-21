<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'title','brand_id','category_id','model_id','description','quantity','status','price','price_ex'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function brand(){
        return $this->hasOne(ProductBrand::class,'id','brand_id');
    }

    public function category(){
        return $this->hasOne(ProductCategory::class,'id','category_id');
    }

    public function model(){
        return $this->hasOne(ProductModel::class,'id','model_id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class,'product_id','id')->orderBy('order');
    }
}
