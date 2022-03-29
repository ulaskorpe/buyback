<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankPurchase extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'bank_purchases';

    protected $fillable = [
       'bank_id','purchase_id','commission','purchase','payment_plan_id','description_id'
    ];

    protected $hidden = [
        'deleted_at','created_at','updated_at'
    ];

    public function bank(){
        return $this->hasOne(Bank::class,'bank_id','bank_id');
    }
}
