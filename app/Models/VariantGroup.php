<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'variant_groups';

    protected $fillable = [
        'group_name','order'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function variants(){
        return $this->hasMany(ProductVariant::class,'group_id','id');
    }
}
