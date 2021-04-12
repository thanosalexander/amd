<?php

return [

    'default' => getenv('WEATHER_PROVIDER', 'openweathermap'),

    'providers' => [

        'openweathermap' => [
            'api_key'=>getenv('OPENWEATHERMAP_API_KEY'),
        ],

    ],
];
