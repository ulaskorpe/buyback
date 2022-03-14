<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelQuestion extends Model
{

    protected $table = 'model_question_pivot';

    protected $fillable = [
        'model_id','question_id','count'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at','id'
    ];

    public function model(){
        return $this->hasOne(ProductModel::class,'id','model_id');
    }

    public function question(){
        return $this->hasOne(Question::class,'id','question_id')->where('status','=',1);
    }


}
