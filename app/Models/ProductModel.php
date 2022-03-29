<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model
{

    use SoftDeletes;
    protected $table = 'product_models';

    protected $fillable = [
        'Brandid','Catid','micro_id','Modelname','memory_id','Imagelarge','Imagethumb','Seotitle','Seodesc','Status','min_price','max_price'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function brand(){
        return $this->hasOne(ProductBrand::class,'id','Brandid');
    }

    Public function category(){
        return $this->hasOne(ProductCategory::class,'id','Catid');
    }

    Public function memory(){
        return $this->hasOne(Memory::class,'id','memory_id');
    }

    public function colors(){
       //  return $this->belongsToMany(ColorModel::class,'color_model_pivot','id','id','color_id','model_id');
         return $this->belongsToMany(Color::class,'color_model_pivot','model_id','color_id')  ;
             //return $this->hasManyThrough(Color::class,ColorModel::class,'id','id','model_id','color_id');
    }

    public function questions(){
        return $this->belongsToMany(Question::class,'model_question_pivot','model_id','question_id')  ;

    }


}
