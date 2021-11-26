<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'countries';

    protected $fillable = [
        'binary_code','triple_code','name','phone_code'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function cities(){
         return $this->hasMany(City::class,'country_id','id');
    }
}
