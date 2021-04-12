<?php


namespace App\Services\Weather;


use App\Services\Weather\Providers\OpenWeatherMap;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class Weather
{
    /**
     * @param string $city
     * @param string|null $provider
     * @return WeatherCityResponse
     * @throws GuzzleException
     */
    public static function getCityTemperature(string $city, string $provider = null): WeatherCityResponse
    {
        $defaultProvider = $provider ?? config('weather.default');

        switch ($defaultProvider) {
            case 'openweathermap' :
                return (new OpenWeatherMap())->fetchCityTemperature($city);
            default:
                throw new Exception('Provider is not set');
        }
    }
}