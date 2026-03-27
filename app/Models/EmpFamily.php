<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpFamily extends Model
{
    use HasFactory;

    protected $table = 'emp_family';

    protected $fillable = [
        'emp_id',
        'name',
        'd_o_b',
        'age',
        'relationship',
        'residing_with',
        'profession',
        'earnings',
    ];

    protected $casts = [
        'residing_with' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

}
