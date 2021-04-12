<?php

use App\Routing\Route;


Route::get('/', 'HomeController@index');
Route::post('/check-weather', 'WeatherController@check');
Route::post('/send-sms', 'SmsController@send');