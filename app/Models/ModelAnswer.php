<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelAnswer extends Model
{
    protected $table = 'model_answers';

    protected $fillable = [
        'model_id','answer_id','value'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at','id'
    ];

    public function model(){
        return $this->hasOne(ProductModel::class,'model_id','id');
    }

    public function answer(){
        return $this->hasOne(Answer::class,'answer_id','id');
    }
}
