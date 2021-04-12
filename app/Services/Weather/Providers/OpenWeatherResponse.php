<?php


namespace App\Services\Weather\Providers;


use App\Services\Weather\WeatherCityResponse;

class OpenWeatherResponse implements WeatherCityResponse
{
    protected $temperature;

    public function __construct(float $temperature)
    {
        $this->setTemperature($temperature);
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * @param float $temperature
     * @return OpenWeatherResponse
     */
    public function setTemperature(float $temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

}