<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnProblem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'return_problems';

    protected $fillable = [
       'description','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

}
