<?php

namespace App\View;


class View
{
    /**
     * Views directory path
     *
     * @var string
     */
    private static $path = __DIR__ . '/../../resources/views';

    /**
     * @param string $view
     * @param array $parameters
     * @return mixed
     */
    public static function render(string $view, array $parameters = array()): string
    {
        return self::getContents(self::$path . '/' . $view . ".php", $parameters);
    }

    /**
     * @param string $file
     * @param array $parameters
     * @return string
     */
    public static function getContents(string $file, array $parameters = array()): string
    {
        extract($parameters, EXTR_SKIP);
        unset($parameters);

        ob_start();
        require $file;
        unset($file);

        return ob_get_clean();
    }
}