<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'areas';

    protected $fillable = [
        'title','txt_1','txt_2','thumb','image','link','status'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

}
