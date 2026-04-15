<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftMaster extends Model
{
    protected $table = 'shift_master';

    protected $fillable = [
        'company_id',
        'code',
        'description',

        'login',
        'login_min',
        'login_max',

        'logout',
        'logout_min',
        'logout_max',

        'lunch_in',
        'lunch_in_min',
        'lunch_in_max',

        'lunch_out',
        'lunch_out_min',
        'lunch_out_max',

        'ot_in',
        'ot_in_min',
        'ot_in_max',

        'ot_out',
        'ot_out_min',
        'ot_out_max',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
