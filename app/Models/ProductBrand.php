<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrand extends Model
{
    use SoftDeletes;
    protected $table = 'product_brands';

    protected $fillable = [
        'BrandName','ImageLarge','Imagethumb','IconPath','Seotitle','Seodesc','description','Status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function models(){
       return $this->hasMany(ProductModel::class,'Brandid','id');
    }

}
