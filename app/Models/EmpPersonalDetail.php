<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpPersonalDetail extends Model
{
    use HasFactory;

    protected $table = 'emp_personal_details';

    protected $fillable = [
        'emp_id',
        'parent_name',
        'marital_status',
        'd_o_b',
        'age',
        'present_address',
        'permanent_address',
        'mobile',
        'religion',
        'physically_challenged',
        'if_yes_details',
        'img_path',
    ];

    protected $casts = [
        'physically_challenged' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

}
