<?php

namespace App\Traits;

trait StoreScope
{
    public function scopeStoreAccess($query)
    {
        if (auth()->check()) {

            // Skip for admin
            if (auth()->user()->is_superadmin == 1) {
                return $query;
            }

            return $query->whereIn(
                'store_id',
                auth()->user()->stores->pluck('id')
            );
        }

        return $query;
    }
}