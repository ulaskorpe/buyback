<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tmp extends Model
{
    protected $table = 'tmp';

    protected $fillable = [
       'title','data'
    ];
}
