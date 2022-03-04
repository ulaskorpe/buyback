<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'news';

    protected $fillable = [
        'title','image','thumb','url','description','content','date','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
