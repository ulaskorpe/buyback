<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubLinkGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sub_link_groups';

    protected $fillable = [
        'menu_sub_item_id','title','link','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
    public function menu_sub_item(){
        return $this->hasOne(MenuSubItem::class,'id','menu_sub_item_id');
    }

    public function menu_links(){
        return $this->hasMany(MenuSubItemLink::class,'group_id','id')->orderBy('order');
    }
}
