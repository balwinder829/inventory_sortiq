<?php

namespace App\Traits;

trait HasPermissions
{
    public function canAccess($permission, $storeId)
    {
        // Super admin → full access
        if ($this->is_superadmin) {
            return true;
        }

        // Use your existing logic
        return $this->hasPermission($permission, $storeId);
    }
}