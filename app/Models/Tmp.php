<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Tmp extends Model
{

    use Prunable;
    protected $table = 'tmp';

    protected $fillable = [
       'title','data'
    ];

    public function prunable()
    {
        return static::where('created_at','<=',now()->subMinute());
    }
}
