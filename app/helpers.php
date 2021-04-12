<?php

/**
 * Gets base url
 *
 * @param string $path
 * @return string | array | null
 * @throws Exception
 */
function config(string $path)
{
    $parts = explode('.', $path);

    if (count($parts) === 0) {
        return null;
    }

    $file = "config/{$parts[0]}.php";

    if (!file_exists($file)) {
        throw new Exception('Config file does not exist!');
    }

    $fileContents = include $file;

    unset($parts[0]);

    $key = implode('.', $parts);

    $array = collect($fileContents);

    if ($key === null) {
        return $array;
    }

    return $array->get($key);
}


function dd($var)
{
    echo "<pre>";
    print_r($var);
    exit;
}

/**
 * Aborts a request
 *
 * @param string $message
 * @param int $code
 */
function abort(string $message = "Page not found", int $code = 404)
{
    http_response_code($code);
    die($message);
}