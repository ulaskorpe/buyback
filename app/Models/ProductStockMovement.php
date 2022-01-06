<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStockMovement extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'product_stock_movements';

    protected $fillable = [
        'product_id','color_id','memory_id','quantity','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function product(){
        return $this->hasOne(Product::class,'product_id','id');
    }
    public function color(){
        return $this->hasOne(Color::class,'id','color_id');
    }
    public function memory(){
        return $this->hasOne(Memory::class,'id','memory_id');
    }
}
