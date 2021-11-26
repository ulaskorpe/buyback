<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyBack extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'buybacks';

    protected $fillable = [
        'buyback_user_id','imei','model_id','color_id','offer_price'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function buybackuser(){
        return $this->hasOne(BuyBackUser::class,'id','buyback_user_id');
    }

    public function model(){
        return $this->hasOne(ProductModel::class,'id','model_id');
    }
    public function color(){
        return $this->hasOne(Color::class,'id','color_id');
    }


}
