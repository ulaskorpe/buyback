<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'banners';

    protected $fillable = [
        'title','image','link','status','order'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
