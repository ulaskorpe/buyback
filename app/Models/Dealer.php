<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use SoftDeletes;
    protected $table = 'dealers';

    protected $fillable = [
        'name','dealer_id','contact_person','phone','phone_alt','city','distinct'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
