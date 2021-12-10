<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'user_groups';

    protected $fillable = [
        'name','users','buybacks','system','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
    public function users(){
        return $this->hasMany(User::class,'group_id','id');
    }

}
