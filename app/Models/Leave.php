<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'emp_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'manager_status',
        'manager_id',
        'manager_action_at',
        'manager_remark',
        'hr_status',
        'hr_action_at',
        'hr_remark',
        'leave_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'emp_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employees::class, 'manager_id');
    }
}
