<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    protected $table = 'answers';

    protected $fillable = [
        'question_id','answer','value','count'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function question(){
        return $this->hasOne(Question::class,'question_id','id');
    }


}
