<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComputedStats extends Model
{
    protected $fillable = [
        'name',
        'value',
        'uri',
    ];
}
