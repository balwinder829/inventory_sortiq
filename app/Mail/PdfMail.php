<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $order; // <-- add this

    public function __construct($pdfContent, $order)
    {
        $this->pdfContent = $pdfContent;
        $this->order = $order; // <-- assign order
    }

    public function build()
    {
        return $this->to($this->order->customer_email, ucwords($this->order->customer_name)) // recipient with name
                    ->subject("Your Invoice #{$this->order->invoice_no} from Sandal Multi Store") // attractive subject
                    ->view('emails.invoice_data') // email body view
                    ->with([
                        'order' => $this->order, // pass order to email view
                    ])
                    ->attachData($this->pdfContent, 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}
