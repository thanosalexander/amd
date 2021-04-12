<?php


namespace App\Services\SMS;


use App\Services\SMS\Providers\Routee\RouteeService;
use Exception;

class Sms
{
    public const SMS_SENT = 'smsSent';
    public const SMS_ERROR = 'smsError';

    /**
     * @throws Exception
     */
    public static function sendSms(string $message, string $to, string $provider = null): SmsResponse
    {
        $defaultProvider = $provider ?? config('sms.default');

        switch ($defaultProvider) {
            case 'routee' :
                return (new RouteeService())->send($message, $to);
            default:
                throw new Exception('Provider is not set');
        }
    }
}