<?php


namespace App\Controllers;


use App\Services\Weather\Weather;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class WeatherController extends Controller
{
    /**
     * @return false|string
     * @throws GuzzleException
     */
    public function check(): string
    {
        try {
            $cityTemperatureResponse = Weather::getCityTemperature("thessaloniki");

            $temperature = $cityTemperatureResponse->getTemperature();

            return $this->responseJSON(200, "Temperature in Thessaloniki is {$temperature} celcius!", [
                'temperature' => $temperature
            ]);
        } catch (Exception $exception) {
            return $this->responseJSON(422, "Temperature could not be fetched!");
        }
    }
}