<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
    protected $table = 'product_colors';

    protected $fillable = [
        'product_id','color_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function color(){
        return $this->hasOne(Color::class,'id','color_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
