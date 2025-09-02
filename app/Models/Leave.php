<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'leave_duration',
        'from_date',
        'to_date',
        'leave_days',
        'reason',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Leave.php
    public function approver1()
    {
        return $this->belongsTo(Employees::class,  'approver_1', 'emp_id');
    }
    public function approver2()
    {
        return $this->belongsTo(Employees::class, 'approver_2', "emp_id");
    }
     public function employees()
    {
        return $this->hasOne(Employees::class, 'user_id');
    }
}
