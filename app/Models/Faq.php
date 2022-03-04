<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'faqs';

    protected $fillable = [
      'title','content','order','status'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
