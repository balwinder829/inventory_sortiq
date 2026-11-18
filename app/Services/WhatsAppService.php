<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

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
        $formattedNumber = $this->formatPhoneNumber($to);

        // If invalid, skip sending
        if (!$formattedNumber) {
            Log::warning("Skipped sending WhatsApp: Invalid phone number => " . $to);
            return null;
        }

        return $this->client->messages->create(
            "whatsapp:" . $formattedNumber,
            [
                "from" => $this->from,
                "body" => $invoiceInfo
            ]
        );
    }

    private function formatPhoneNumber($number)
    {
        // Remove spaces, dashes, brackets, etc.
        $number = preg_replace('/\D/', '', $number);

        // If number starts with 0 and has 11 digits -> remove leading 0
        if (strlen($number) === 11 && substr($number, 0, 1) === '0') {
            $number = substr($number, 1);
        }

        // If 10 digits, assume Indian number, prepend +91
        if (strlen($number) === 10) {
            return '+91' . $number;
        }

        // If 12 digits and starts with 91, just prepend +
        if (strlen($number) === 12 && substr($number, 0, 2) === '91') {
            return '+' . $number;
        }

        // If already in correct format with +91
        if (strlen($number) === 13 && substr($number, 0, 3) === '+91') {
            return $number;
        }

        // Invalid number
        return null;
    }
}
