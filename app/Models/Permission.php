<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'menu_group',
        'menu_group_order',
        'menu_item',
        'menu_item_order',
    ];

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_permissions');
    // }
}