<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public $timestamps = false; // if you don't have created_at/updated_at
}
