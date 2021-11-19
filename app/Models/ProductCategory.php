<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;
    protected $table = "product_categories";

    protected $fillable = [
        'category_name'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function models(){
        return $this->hasMany(ProductModel::class,'Catid','id');
    }

}
