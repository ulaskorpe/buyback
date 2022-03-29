<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'contacts';

    protected $fillable = [
        'customer_id','guid','name','surname','email','phone_number','subject','message','status'

    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function customer(){
      return $this->hasOne(Customer::class,'customer_id','customer_id');
    }

    public function guest(){
        return $this->hasOne(Guest::class,'guid','guid');
    }
}
