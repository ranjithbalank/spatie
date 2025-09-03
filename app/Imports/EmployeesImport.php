<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employees;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon; // Use this for date formatting if needed


class EmployeesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // @dd($row);
        $employeeEmail = $row['email'] ?? null;
        if (!$employeeEmail) {
            return null; // Skip if no email is provided
        }

        // Step 1: Find or create the User record
        $user = User::updateOrCreate(
            ['email' => $employeeEmail],
            [
                'name'          => $row['emp_name'],
                'password'      => Hash::make('dmw@2025'), // Default password
                'status'        => $row['status'] ?? 'active',
                'leave_balance' => $row['leave_balance'] ?? 20.0,
            ]
        );

        // Step 2: Use the user ID to find or create the EmployeeDetails record
        Employees::updateOrCreate(
            ['user_id' => $user->id],
            [
                'emp_id'        => $row['emp_id'] ?? null,
                'emp_name'      => $row['emp_name'],
                'gender'        => $row['gender'] ?? null,
                'unit_id'       => $row['unit_id'] ?? null,
                'department_id' => $row['department_id'] ?? null,
                'dob'           => isset($row['dob']) ? Carbon::parse($row['dob']) : null,
                'doj'           => isset($row['doj']) ? Carbon::parse($row['doj']) : null,
                'dor'           => isset($row['dor']) ? Carbon::parse($row['dor']) : null,
                'shift_type'    => $row['shift_type'] ?? null,
                'manager_id'    => $row['manager_id'] ?? null,
                'designation_id' => $row['designation_id'] ?? null,
                'status'        => $row['status'] ?? 'active',
            ]
        );

        return $user;
    }
}
