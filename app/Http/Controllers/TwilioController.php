<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function callback(Request $request)
    {
        debug_log('callback called and inside');
        try {
            $postData = $request->all();
            debug_log('callback data', [$postData]);

            if ($this->hasRequiredParameters($postData)) {
                debug_log('has required Parameters');
                $this->updateAlertLog($postData['SmsSid'], 'Twilio ' . $postData['SmsStatus']);
                debug_log('updated');
                return response()->json(['success' => true], 200);
            } else {
                debug_log('no params');
                return response()->json(['error' => 'Missing parameters in the request'], 400);
            }
        } catch (\Exception $e) {
            debug_log('error', [$e]);
            Log::error('Error in Twilio callback', ['exception' => $e]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    private function hasRequiredParameters(array $postData): bool
    {
        return isset($postData['SmsSid']) && isset($postData['SmsStatus']);
    }

    private function updateAlertLog(string $smsSid, string $smsStatus): void
    {
        DB::table('alert_logs')
            ->where('sms_messageId', $smsSid)
            ->update([
                'sms_status_updated' => now(),
                'sms_status' => $smsStatus,
            ]);
    }
}
