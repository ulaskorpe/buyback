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
        'title','micro_id','brand_id','category_id','model_id','description','status','price','price_ex','calculated_vote'

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

    public function firstImage(){
        return $this->hasMany(ProductImage::class,'product_id','id')->where('first','=',1);
    }

    public function colors(){
        //return $this->hasMany(Color)
        return $this->hasManyThrough(Color::class,ProductColor::class,'product_id','id','id','color_id');
    }
    public function memories(){
        //return $this->hasMany(Color)
        return $this->hasManyThrough(Memory::class,ProductMemory::class,'product_id','id','id','memory_id');
    }
//    public function memories(){
//        return $this->hasMany(ProductMemory::class,'product_id','id');
//    }
}
