<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImeiQuery extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'imei_queries';

    protected $fillable = [
        'imei','user_id','model_id','result','token','token_type','scope','ip_address'
    ];

    protected $hidden = [
       'created_at','updated_at','deleted_at'
    ];


    public function model(){

    }
}
