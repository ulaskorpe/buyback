<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class NewsLetter extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'newsletters';

    protected $fillable = [
        'customer_id','guid','email','status'

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
