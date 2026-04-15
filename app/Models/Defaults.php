<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Defaults extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $table = 'defaults';
}
