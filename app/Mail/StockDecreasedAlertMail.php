<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Mail\Mailable;

class StockDecreasedAlertMail extends Mailable
{
    public $product;
    public $oldQty;
    public $newQty;

    public function __construct(Item $product, $oldQty, $newQty)
    {
        $this->product = $product;
        $this->oldQty = $oldQty;
        $this->newQty = $newQty;
    }

    public function build()
    {
        return $this->subject('📉 Stock Decreased Alert: ' . $this->product->name)
                    ->markdown('emails.stock_decreased');
    }
}
