<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyBackAnswer extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'buyback_answers';

    protected $fillable = [
        'buyback_id','question_id','answer_id','value'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function buyback(){
        return $this->hasOne(BuyBack::class,'id','buyback_id');
    }
    public function question(){
        return $this->hasOne(Question::class,'id','question_id');
    }
    public function answer(){
        return $this->hasOne(Answer::class,'id','answer_id');
    }
}
