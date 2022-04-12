<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'areas';

    protected $fillable = [
        'title','txt_1','txt_2','url','type','textStyle','thumb','image','link','micro_id','status'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

}
