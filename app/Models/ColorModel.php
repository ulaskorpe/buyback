<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColorModel extends Model
{



    protected $table = "color_model_pivot";

    protected $fillable = [
        'model_id','color_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function model(){
        return $this->hasOne(ProductModel::class,'model_id','id');
    }

    public function color(){
        return $this->hasOne(Color::class,'color_id','id');
    }
}
