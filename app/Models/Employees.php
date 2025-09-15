<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    //
    protected $table = 'employees_details';
    protected $fillable = [
        'emp_id',
        'user_id',
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
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function managedEmployees()
    {
        return $this->hasMany(Employees::class, 'manager_id');
    }

    public function manager()
    {
        // employee belongs to one manager (who is also an employee)
        return $this->belongsTo(Employees::class, 'manager_id', 'emp_id');
    }
    public function subordinates()
    {
        return $this->hasMany(Employees::class, 'manager_id');
    }
    public function users()
    {
        // An Employee belongs to a User
        return $this->belongsTo(User::class);
    }
}
