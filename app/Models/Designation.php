<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
