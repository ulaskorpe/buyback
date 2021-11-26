<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Town extends Model
{
    use HasFactory;


    use SoftDeletes;
    protected $table = 'towns';

    protected $fillable = [
        'city_id','name'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }

    public function districts(){
        return $this->hasMany(District::class,'town_id','id');
    }

}
