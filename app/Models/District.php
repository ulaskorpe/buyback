<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'districts';

    protected $fillable = [
        'town_id','name'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function town(){
        return $this->hasOne(Town::class,'id','town_id');
    }

    public function neighboors(){
        return $this->hasMany(Neighborhood::class,'district_id','id');
    }
}
