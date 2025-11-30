<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComputedStats extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'name';
    public $incrementing = false;
}
