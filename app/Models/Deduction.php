<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'employee_id',
        'from_date',
        'to_date',
        'type',
        'percentage',
        'amount',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
