<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HumanResource extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'human_resources';

    protected $fillable = [
     'name','surname','expectation','department','cv_file'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
