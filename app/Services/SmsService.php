<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS to international phone number
     * Using a generic HTTP-based SMS API approach
     * 
     * Note: You'll need to configure your SMS provider credentials in .env:
     * SMS_PROVIDER=twilio (or your provider)
     * SMS_API_KEY=your_api_key
     * SMS_API_SECRET=your_api_secret
     * SMS_FROM_NUMBER=your_from_number
     */
    public function send(string $to, string $message): bool
    {
        try {
            $provider = config('services.sms.provider', 'twilio');
            
            return match($provider) {
                'twilio' => $this->sendViaTwilio($to, $message),
                'nexmo' => $this->sendViaNexmo($to, $message),
                'log' => $this->sendViaLog($to, $message), // For development/testing
                default => $this->sendViaLog($to, $message)
            };
        } catch (Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage(), [
                'to' => $to,
                'message' => $message
            ]);
            return false;
        }
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendViaTwilio(string $to, string $message): bool
    {
        $accountSid = config('services.sms.twilio.account_sid');
        $authToken = config('services.sms.twilio.auth_token');
        $fromNumber = config('services.sms.twilio.from_number');

        if (!$accountSid || !$authToken || !$fromNumber) {
            Log::warning('Twilio credentials not configured, logging SMS instead');
            return $this->sendViaLog($to, $message);
        }

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => $fromNumber,
                'To' => $this->formatInternationalNumber($to),
                'Body' => $message,
            ]);

        if ($response->successful()) {
            Log::info('SMS sent via Twilio', ['to' => $to]);
            return true;
        }

        Log::error('Twilio SMS failed', [
            'to' => $to,
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        return false;
    }

    /**
     * Send SMS via Nexmo/Vonage
     */
    protected function sendViaNexmo(string $to, string $message): bool
    {
        $apiKey = config('services.sms.nexmo.api_key');
        $apiSecret = config('services.sms.nexmo.api_secret');
        $fromName = config('services.sms.nexmo.from_name', 'Ryven');

        if (!$apiKey || !$apiSecret) {
            Log::warning('Nexmo credentials not configured, logging SMS instead');
            return $this->sendViaLog($to, $message);
        }

        $response = Http::asJson()->post('https://rest.nexmo.com/sms/json', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'from' => $fromName,
            'to' => $this->formatInternationalNumber($to),
            'text' => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['messages'][0]['status']) && $data['messages'][0]['status'] === '0') {
                Log::info('SMS sent via Nexmo', ['to' => $to]);
                return true;
            }
        }

        Log::error('Nexmo SMS failed', [
            'to' => $to,
            'response' => $response->json()
        ]);
        return false;
    }

    /**
     * Log SMS instead of sending (for development/testing)
     */
    protected function sendViaLog(string $to, string $message): bool
    {
        Log::info('SMS (Logged - Not Sent)', [
            'to' => $to,
            'message' => $message,
            'note' => 'Configure SMS provider in .env to send actual SMS'
        ]);
        return true;
    }

    /**
     * Format phone number to international format
     * Ensures the number starts with +
     */
    protected function formatInternationalNumber(string $number): string
    {
        $number = preg_replace('/[^0-9+]/', '', $number);
        
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }
        
        return $number;
    }
}
