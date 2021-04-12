<?php


namespace App\Services\SMS\Providers\Routee;


use App\Services\SMS\Sms;
use App\Services\SMS\SmsResponse;
use Illuminate\Support\Collection;

class RouteeResponse implements SmsResponse
{
    protected $status;

    protected $info;


    /**
     * RouteeResponse constructor.
     *
     * @param array $options
     * @param string $status
     */
    public function __construct(string $status, array $options = [])
    {
        $this->setInfo(collect($options));
        $this->setStatus($status);
    }

    /**
     * @return Collection
     */
    public function getInfo(): Collection
    {
        return $this->info;
    }

    /**
     * @param Collection $info
     * @return RouteeResponse
     */
    public function setInfo(Collection $info): RouteeResponse
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccessfull(): bool
    {
        return $this->getStatus() === Sms::SMS_SENT;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return RouteeResponse
     */
    public function setStatus(string $status): RouteeResponse
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getStatus() === Sms::SMS_ERROR;
    }
}