<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoCompanyBranch extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cargo_company_branches';

    protected $fillable = [
        'cargo_company_id','title','person','neighborhood_id','distinct_id','town_id','city_id','phone_number','phone_number_2','active'


    ];

    public function company(){
        return $this->hasOne(CargoCompany::class,'id','cargo_company_id');
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
