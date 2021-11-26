<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighborhood extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'districts';

    protected $fillable = [
        'district_id','name'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function district(){
        return $this->hasOne(Town::class,'id','district_id');
    }


}
