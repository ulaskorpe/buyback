<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'banks';

    protected $fillable = [
        'bank_name','bank_id'
    ];

    protected $hidden = [
        'deleted_at','created_at','updated_at'
    ];


    public function purchases(){
        return $this->hasMany(BankPurchase::class,'bank_id','bank_id');
    }
}
