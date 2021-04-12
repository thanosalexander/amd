<?php

return [

    'default' => getenv('SMS_PROVIDER', 'routee'),

    'providers' => [
        'routee' => [
            'id'=>getenv('ROUTEE_API_ID'),
            'secret'=>getenv('ROUTEE_API_SECRET'),
        ],

    ],
];
