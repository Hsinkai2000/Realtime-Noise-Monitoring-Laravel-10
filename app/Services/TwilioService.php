<?php

namespace App\Services;

use Twilio\Rest\Client;
use View;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }
    public function sendMessage($to, $template, $data)
    {
        try {
            $message = view($template, ['data' => $data])->render();

            $response = $this->twilio->messages->create($to, [
                'from' => config('services.twilio.from'),
                'body' => $message,
                'riskCheck' => 'disable',
                // 'statusCallback' => config('services.twilio.callback_url'),
            ]);

            debug_log("SMS sent successfully to to: {$data["client_name"]}");

            return $response;
        } catch (\Exception $e) {
            debug_log("Error sending SMS to $to: " . $e->getMessage());

            return null;
        }
    }
}
