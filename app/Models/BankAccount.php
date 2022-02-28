<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'bank_accounts';

    protected $fillable = [
        'bank_name','name_surname','branch','account_number','iban','status'
    ];

    protected $hidden = [
        'deleted_at'
    ];


}
