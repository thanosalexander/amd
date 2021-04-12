<?php

namespace App\Controllers;

use App\Services\SMS\Sms;
use Exception;

class SmsController extends Controller
{
    public function send()
    {
        $responseCode = 422;
        $responseMessage = "Sms could not be sent!";
        $responseData = [];

        try {
            $message = $_POST['message'];
            $sendTo = "+306978745957";

            $response = Sms::sendSms($message, $sendTo);

            if ($response->isSuccessfull()) {
                $responseCode = 200;
                $responseMessage = "Sms sent!";
                $responseData = [];
            }
        } catch (Exception $exception) {
        }

        return $this->responseJSON($responseCode, $responseMessage, $responseData);
    }
}