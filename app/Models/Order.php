<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Symfony\Component\Translation\t;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders';

    protected $fillable = [
        'order_code','name_surname','cargo_company_id','cargo_company_branch_id',
        'order_method','cargo_code','customer_id','guid','customer_address_id','invoice_address_id','service_address_id','status',
        'amount','banka_id','taksit','message','return_problem_id'

    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function payment_method(){
        return $this->hasOne(BankAccount::class,'id','payment_method');
    }

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function guest(){
        return $this->hasOne(Guest::class,'id','guid');
    }
    public function banka(){
        return $this->hasOne(Bank::class,'bank_id','banka_id');
    }

    public function customer_address(){
        return $this->hasOne(CustomerAddress::class,'id','customer_address_id');
    }
    public function return_problem(){
        return $this->hasOne(ReturnProblem::class,'id','return_problem_id');
    }

    public function invoice_address(){
        return $this->hasOne(CustomerAddress::class,'id','invoice_address_id');
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

    public function cart_items(){
       //return $this->hasManyThrough(CartItem::class,CartOrderPivot::class,'order_id','id');
        return $this->hasMany(CartItem::class,'order_id','id');
    }
    public function guest_cart_items(){
        //return $this->hasManyThrough(CartItem::class,CartOrderPivot::class,'order_id','id');
        return $this->hasMany(GuestCartItem::class,'order_id','id');
    }
    public function order_method(){
        return $this->hasOne(BankAccount::class,'id','order_method');
    }



}
