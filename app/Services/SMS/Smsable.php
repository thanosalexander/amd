<?php

namespace App\Services\SMS;

interface Smsable
{
    /**
     * @param string $message
     * @param string $to
     * @return SmsResponse
     */
    public function send(string $message, string $to): SmsResponse;
}