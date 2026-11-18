<?php

// app/Mail/LowStockAlertMail.php
namespace App\Mail;

use App\Models\Item;
use Illuminate\Mail\Mailable;

class LowStockAlertMail extends Mailable
{
    public $product;

    public function __construct(Item $product)
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject('⚠️ Low Stock Alert: ' . $this->product->name)
                    ->markdown('emails.low_stock_alert');
    }
}
