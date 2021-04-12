<?php


namespace App\Services\Weather;


interface WeatherCityResponse
{
    /**
     * Get temperature for a specific city
     *
     * @return float
     */
    public function getTemperature(): float;
}