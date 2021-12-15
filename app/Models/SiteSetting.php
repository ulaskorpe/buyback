<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    use HasFactory;
     use SoftDeletes;
    protected $table = 'site_settings';

    protected $fillable = [
        'setting_name','description','value','code'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at'
    ];
}
