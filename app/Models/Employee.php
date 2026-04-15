<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'actual_emp_id',
        'code',
        'name',
        'gender',
        'd_o_j',
        'd_o_l',
        'status',
        'audit',
        'company_id',
        'dept_id',
        'cat_id',
        'des_id',
        'sal_type',
        'salary',
        'esi_eligible',
        'esi_number',
        'pf_number',
    ];

    protected $casts = [
        'status' => 'boolean',
        'audit' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'des_id');
    }

    public function family()
    {
        return $this->hasMany(EmpFamily::class, 'emp_id');
    }

    public function personalDetail()
    {
        return $this->hasOne(EmpPersonalDetail::class, 'emp_id');
    }

    public function nominees()
    {
        return $this->hasMany(EmpNominee::class, 'emp_id');
    }
}
