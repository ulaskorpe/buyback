<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;
    protected $table = "colors";

    protected $fillable = [
        'color_name','color_code','sample','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];


}
