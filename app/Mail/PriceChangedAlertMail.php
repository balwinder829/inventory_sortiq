<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Mail\Mailable;

class PriceChangedAlertMail extends Mailable
{
    public $product;
    public $oldPrice;
    public $newPrice;
    public $oldCost;
    public $newCost;

    public function __construct(Item $product, $oldPrice, $newPrice, $oldCost, $newCost)
    {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->oldCost = $oldCost;
        $this->newCost = $newCost;
    }

    public function build()
    {
        return $this->subject('🔔 Price Change Alert: ' . $this->product->name)
                    ->markdown('emails.price_changed');
    }
}
