<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_addresses';

    protected $fillable = [
       'customer_id','title','name_surname','neighborhood_id','distinct_id','town_id','city_id','phone_number','phone_number_2','first'


    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function neighborhood(){
        return $this->hasOne(Neighborhood::class,'id','neighborhood_id');
    }
    public function district(){
        return $this->hasOne(District::class,'id','district_id');
    }
    public function town(){
        return $this->hasOne(Town::class,'id','town_id');
    }
    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }
}
