<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'menu_items';

    protected $fillable = [
       'title','link','thumb','image','location','count_sub','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function sub_items(){
        return $this->hasMany(MenuSubItem::class,'menu_id','id')
            ->where('status','=',1)
            ->orderBy('order','DESC');
    }
}
