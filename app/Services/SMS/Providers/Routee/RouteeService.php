<?php

namespace App\Services\SMS\Providers\Routee;

use App\Services\SMS\Sms;
use App\Services\SMS\Smsable;
use App\Services\SMS\SmsResponse;
use Exception;
use Illuminate\Support\Collection;

class RouteeService implements Smsable
{
    /**
     * @var Collection $options
     */
    protected $options;

    protected $authenticationInfo;

    public function __construct()
    {
        $options = collect(config('sms.providers')['routee']);

        $this->setOptions($options);

        $this->setAuthorizationKey();

        $this->authenticate();
    }

    /**
     * @return $this
     */
    protected function setAuthorizationKey(): RouteeService
    {
        $this->options->offsetSet('authorizationKey', base64_encode(
            $this->getOptions()->offsetGet('id') . ":" . $this->getOptions()->offsetGet('secret')
        ));

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Collection $options
     * @return RouteeService
     */
    public function setOptions(Collection $options): RouteeService
    {
        $this->options = $options;

        return $this;
    }

    protected function authenticate()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://auth.routee.net/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $this->getAuthorizationKey(),
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception('Could not be authenticated!');
        } else {
            $this->setAuthenticationInfo(collect(json_decode($response, true)));
        }
    }

    /**
     * @return string
     */
    protected function getAuthorizationKey(): string
    {
        return $this->getOptions()->offsetGet('authorizationKey');
    }

    public function send(string $message, string $to): SmsResponse
    {
        if (!$this->canSendSms()) {
            throw new Exception('Sms could not be send! Access token is wrong/missing!');
        }

        $status = Sms::SMS_SENT;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://connect.routee.net/sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'body' => $message,
                'to' => $to,
                'from' => 'amdTelecom'
            ]),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $this->getAccessTokenKey(),
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $status = Sms::SMS_ERROR;
        }

        return (new RouteeResponse($status, json_decode($response, true)));
    }

    /**
     * @return bool
     */
    protected function canSendSms(): bool
    {
        return $this->getAuthenticationInfo()->has('access_token');
    }

    /**
     * @return Collection
     */
    public function getAuthenticationInfo(): Collection
    {
        return $this->authenticationInfo;
    }

    /**
     * @param Collection $authenticationInfo
     * @return RouteeService
     */
    public function setAuthenticationInfo(Collection $authenticationInfo): RouteeService
    {
        $this->authenticationInfo = $authenticationInfo;

        return $this;
    }

    /**
     * @return string
     */
    protected function getAccessTokenKey(): string
    {
        return $this->getAuthenticationInfo()->offsetGet('access_token');
    }
}