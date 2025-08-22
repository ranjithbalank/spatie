<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    //
    protected $table = 'employees_details';
       protected $fillable = [
        'emp_id',
        'emp_name',
        'manager_id',
        'unit_id',
        'department_id',
        'designation_id',
        'doj',
        'dor',
        'dob',
        'leave_balance',
        'status',
        'user_id',
        'created_by',
        'updated_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
