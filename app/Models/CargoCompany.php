<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cargo_companies';

    protected $fillable = [
       'name','person','logo','email','phone_number','status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function branches(){
        return $this->hasMany(CargoCompanyBranch::class,'cargo_company_id','id');
    }

}
