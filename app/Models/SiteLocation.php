<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteLocation extends Model
{
    use HasFactory;

    protected $table = 'site_locations';

    protected $fillable = [
        'name','status'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];


}
