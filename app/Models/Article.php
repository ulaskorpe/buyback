<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'articles';

    protected $fillable = [
        'title','prologue','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function parts(){
        return $this->hasMany(ArticlePart::class,'article_id','id')->orderBy('count');
    }

}
