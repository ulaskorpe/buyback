<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductLocation extends Model
{
    use HasFactory;

    protected $table = 'product_locations';

    protected $fillable = [
        'location_id','product_id','order'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function location(){
        return $this->hasOne(SiteLocation::class,'id','location_id');
    }
}
