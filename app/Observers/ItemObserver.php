<?php

namespace App\Observers;

use App\Models\Item;
use App\Mail\PriceChangedAlertMail;
use App\Mail\StockDecreasedAlertMail;
use Illuminate\Support\Facades\Mail;

class ItemObserver
{
    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        $adminEmail = config('app.admin_email', 'hs875147@gmail.com');

        // 1. Price Changes Alert
        if ($item->wasChanged('price') || $item->wasChanged('cost_price')) {
            $oldPrice = $item->getOriginal('price');
            $newPrice = $item->price;
            $oldCost = $item->getOriginal('cost_price');
            $newCost = $item->cost_price;

            try {
                Mail::to($adminEmail)->send(new PriceChangedAlertMail($item, $oldPrice, $newPrice, $oldCost, $newCost));
            } catch (\Throwable $e) {
                \Log::error('Error sending price change email: ' . $e->getMessage());
            }
        }

        // 2. Decreased Stock Alert
        if ($item->wasChanged('quantity')) {
            $oldQty = $item->getOriginal('quantity');
            $newQty = $item->quantity;

            if ($newQty < $oldQty) {
                try {
                    Mail::to($adminEmail)->send(new StockDecreasedAlertMail($item, $oldQty, $newQty));
                } catch (\Throwable $e) {
                    \Log::error('Error sending stock decreased email: ' . $e->getMessage());
                }
            }
        }
    }
}
