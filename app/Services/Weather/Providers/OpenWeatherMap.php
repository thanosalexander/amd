<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Weatherable;
use App\Services\Weather\WeatherCityResponse;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

class OpenWeatherMap implements Weatherable
{
    protected $client;

    protected $options;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = new Client();

        $options = collect(config('weather.providers')['openweathermap']);

        $this->setOptions($options);
    }

    /**
     * @param string $city
     * @return WeatherCityResponse
     * @throws GuzzleException
     */
    public function fetchCityTemperature(string $city): WeatherCityResponse
    {
        $api_key = $this->options->offsetGet('api_key');

        $response = $this->client->get("api.openweathermap.org/data/2.5/weather?q={$city}&appid={$api_key}&units=metric");

        $temperature = (float)collect(json_decode($response->getBody()->getContents(), true))->get('main')['temp'];

        return (new OpenWeatherResponse($temperature));
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
     * @return OpenWeatherMap
     */
    public function setOptions(Collection $options): OpenWeatherMap
    {
        $this->options = $options;

        return $this;
    }

}