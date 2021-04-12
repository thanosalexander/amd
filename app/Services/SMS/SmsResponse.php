<?php

namespace App\Services\SMS;

use Illuminate\Support\Collection;

interface SmsResponse
{
    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return Collection
     */
    public function getInfo(): Collection;

    /**
     * @return bool
     */
    public function isSuccessfull(): bool;

    /**
     * @return bool
     */
    public function isError(): bool;
}