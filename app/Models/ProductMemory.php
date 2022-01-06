<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMemory extends Model
{
    use HasFactory;
    protected $table = 'product_memories';

    protected $fillable = [
        'product_id','memory_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function memory(){
        return $this->hasOne(Memory::class,'id','memory_id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
