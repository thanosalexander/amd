<?php

namespace App\Services\Weather;

interface Weatherable
{
    /**
     * @param string $city
     * @return WeatherCityResponse
     */
    public function fetchCityTemperature(string $city): WeatherCityResponse;
}