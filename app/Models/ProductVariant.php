<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_variants';

    protected $fillable = [
        'group_id','variant_name','order','binary'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function group(){
        return $this->hasOne(VariantGroup::class,'group_id','id');
    }

    public function values(){
        return $this->hasMany(VariantValue::class,'variant_id','id');
    }
}
