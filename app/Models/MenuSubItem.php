<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuSubItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'menu_sub_items';

    protected $fillable = [
        'menu_id','title','link','thumb','image','location','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function menu_item(){
        return $this->hasOne(MenuItem::class,'id','menu_id');
    }
}
