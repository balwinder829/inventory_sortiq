<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class LowStockSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $items;
    public $adminName;

    public function __construct($items, $adminName = null)
    {
        $this->items = $items;
        $this->adminName = $adminName;
    }

    public function build()
    {
        return $this->subject('⚠️ Low Stock Alert — ' . $this->items->count() . ' Item(s) Low')
                    ->markdown('emails.low_stock_summary');
    }
}
