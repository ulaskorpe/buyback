<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticlePart extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'article_parts';

    protected $fillable = [
        'article_id','title','image','thumb','paragraph','count','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function article(){
        return $this->hasOne(Article::class,'id','article_id');
    }
}
