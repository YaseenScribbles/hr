<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'shift_id',
        'status',
        'remarks',
        'log_in',
        'lunch_out',
        'lunch_in',
        'log_out',
        'actual_hours',
        'ot_in',
        'ot_out',
        'total_hours',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(ShiftMaster::class, 'shift_id');
    }

}
