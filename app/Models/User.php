<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * Represents an application user.
 * Integrates authentication, notifications, and role/permission management.
 *
 * Author: Ranjithbalan K
 * Date: 2025-09-17
 * Version: v1.0.0
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     * Allows bulk assignment via create/update methods.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',           // Full name of the user
        'email',          // Email for login
        'password',       // Hashed password
        'role_id',        // Role ID reference
        'leave_balance',  // Available leave days
        'status',         // User status (active/inactive)
        'last_login_at',  // Timestamp of last login
        'last_logout_at', // Timestamp of last logout
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Hide password in arrays/JSON
        'remember_token',  // Hide remember token
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',  // Cast login timestamp to datetime
            'last_logout_at' => 'datetime', // Cast logout timestamp to datetime
        ];
    }

    /**
     * Relationship: User belongs to a Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: User has one Employee profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employees()
    {
        return $this->hasOne(Employees::class, 'user_id');
    }

    /**
     * Self-referential relationship: User belongs to another user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship: User manages multiple employees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managedEmployees()
    {
        return $this->hasMany(Employees::class, 'manager_id', 'id');
    }
}
