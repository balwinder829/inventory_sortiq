<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;

class CheckLowStockAfterLogin
{
    /**
     * Handle the event.
     */

    public function handle(Authenticated $event): void
    {
        $user = $event->user;

        // Only superadmin should see low stock alerts
        if (!($user->is_superadmin ?? false)) {
            return;
        }

        // If dismissed earlier by the user, do not show again
        if (Session::get('dismiss_low_stock_alert')) {
            return;
        }

        // Clear previous alert to prevent stale data
        Session::forget('low_stock_alert');

        // Fetch low-stock items safely
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('items')) {
                return;
            }

            $lowStock = Item::lowStock()->get();

            if ($lowStock->count() > 0) {
                Session::put('low_stock_alert', [
                    'count'    => $lowStock->count(),
                    'products' => $lowStock->take(5),
                    'limit'    => config('app.low_stock_limit', 10)
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Low stock check skipped: ' . $e->getMessage());
        }
    }


}
