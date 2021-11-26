<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cities';

    protected $fillable = [
        'country_id','name','plate_no','phone_code'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function country(){
        return $this->hasOne(Country::class,'id','country_id');
    }

    public function towns(){
        return $this->hasMany(Town::class,'city_id','id');
    }
}
