<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpNominee extends Model
{
    use HasFactory;

    protected $table = 'emp_nominees';

    protected $fillable = [
        'emp_id',
        'name',
        'relationship',
        'residing_with',
        'd_o_b',
        'age',
        'profession',
        'salary',
        'address',
    ];

    protected $casts = [
        'residing_with' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

}
