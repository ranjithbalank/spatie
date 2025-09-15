<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id',
        'designation_id',
        'department_id',
        'place_of_visit',
        'purpose_of_visit',
        'start_date',
        'end_date',
        'mode_of_travel',
        'km_travelled',
        'pnr_number',
        'toll_cost',
        'total_travel_cost',
        'paid_by',
        'uploaded_travel_ticket_path',
        'accommodation_type',
        'hotel_name',
        'number_of_nights',
        'cost_per_night',
        'total_accommodation_cost',
        'uploaded_accommodation_bill_path',
        'enclosed_accommodation_bill',
        'local_conveyance_cost',
        'uploaded_local_conveyance_bill_path',
        'mode_of_local_conveyance',
        'any_other_expenses_description',
        'any_other_expenses_cost',
        'uploaded_any_other_expenses_bill_path',
        'total_expense_claimed',
        'employee_signed',
        'hod_approval_status',
        'advance_paid',
        'refund_received',
        'exception_approval_status',
        'approved_by_hod',
        'approved_by_cmd',
    ];
}
