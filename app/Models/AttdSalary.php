<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttdSalary extends Model
{
    protected $table = 'attd_salary';

    protected $fillable = [
        'employee_id',
        'from_date',
        'to_date',
        'worked_days',
        'worked_shift',
        'holiday_days',
        'absent_days',
        'wages',
        'gross_salary',
        'esi',
        'pf',
        'advance',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
