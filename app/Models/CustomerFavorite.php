<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFavorite extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_favorites';

    protected $fillable = [
        'customer_id','product_id','memory_id','color_id'



    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function memory(){
        return $this->hasOne(Memory::class,'id','memory_id');
    }

    public function color(){
        return $this->hasOne(Color::class,'id','color_id');
    }
}
