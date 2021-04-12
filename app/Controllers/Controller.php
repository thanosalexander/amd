<?php

namespace App\Controllers;

class Controller
{
    public function responseJSON(int $code = 200, string $message = null, array $data = [])
    {
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");
        http_response_code($code);

        return json_encode([
            'message' => $message,
            'data' => $data
        ]);
    }
}