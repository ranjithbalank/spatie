<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRolePermission extends Model
{
    protected $fillable = ['menu_id', 'role_id', 'action'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class);
    }
}
