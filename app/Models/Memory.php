<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memory extends Model
{
    use SoftDeletes;
    protected $table = 'memories';

    protected $fillable = [
        'memory_value','Status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function models(){
        return $this->hasMany(ProductModel::class,'Brandid','id');
    }
}
