<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_images';

    protected $fillable = [
        'product_id','thumb','image','order','first','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function product(){
        return $this->hasOne(Product::class,'product_id','id');
    }
}
