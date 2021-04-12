<?php

use App\Helpers\DotEnv;
use App\Routing\Route;

require 'vendor/autoload.php';

(new DotEnv(__DIR__ . '/.env'))->load();

require 'app/routes/web.php';

require 'app/helpers.php';

Route::handleRequest($_SERVER['REQUEST_URI']);