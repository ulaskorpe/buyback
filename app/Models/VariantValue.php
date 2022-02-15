<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    use HasFactory;
    protected $table = 'variant_values';

    protected $fillable = [
        'variant_id','value','order'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
    public function variant(){
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }
}
