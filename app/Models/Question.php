<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $table = 'questions';

    protected $fillable = [
        'question','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function answers(){
        return $this->hasMany(Answer::class,'question_id','id')->orderBy('answers.count');
    }
}
