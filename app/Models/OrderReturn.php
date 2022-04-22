<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'order_returns';

    protected $fillable = [
      'return_code',  'order_id','name_surname','cargo_company_id','cargo_company_branch_id','cargo_code','customer_address_id','service_address_id',
        'iban','iban_name','bank_name','status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }


    public function customer_address(){
        return $this->hasOne(CustomerAddress::class,'id','customer_address_id');
    }
    public function cargo_company(){
        return $this->hasOne(CargoCompany::class,'id','cargo_company_id');
    }
    public function cargo_company_branch(){
        return $this->hasOne(CargoCompanyBranch::class,'id','cargo_company_branch_id');
    }


    public function service_address(){
        return $this->hasOne(ServiceAddress::class,'id','customer_id');
    }

}
