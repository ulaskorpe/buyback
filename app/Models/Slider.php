<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'sliders';

    protected $fillable = [
        'thumb','image','bgimg','bgthumb','title','subtitle','btn_1','btn_2','link','micro_id','note','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
