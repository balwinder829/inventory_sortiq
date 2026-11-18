<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $this->from = env('TWILIO_WHATSAPP_FROM');
    }

    public function sendInvoice($to, $invoiceInfo)
    {
        return $this->client->messages->create(
            "whatsapp:" . $to, // recipient must be in format: whatsapp:+91xxxxxxx
            [
                "from" => $this->from,
                "body" => $invoiceInfo
            ]
        );
    }
}
