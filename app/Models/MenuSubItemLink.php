<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuSubItemLink extends Model
{
        use HasFactory;
        use SoftDeletes;
    protected $table = 'menu_sub_item_links';

    protected $fillable = [
        'group_id','title','link','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function group(){
        return $this->hasOne(SubLinkGroup::class,'id','group_id');
    }
}
