<?php

namespace App\Routing;

class Route
{
    /**
     * Keep all the routes
     *
     * @var array
     */
    private static $routes = array();

    /**
     * Route Request Method
     *
     * @var string
     */
    private $method;

    /**
     * Route Path
     *
     * @var string
     */
    private $path;

    /**
     * Route Action
     *
     * @var string
     */
    private $action;

    /**
     * @param string $method
     * @param string $path
     * @param string $action
     */
    public function __construct(string $method, string $path, string $action)
    {
        $this->setMethod($method);
        $this->setPath($path);
        $this->setAction($action);
    }

    /**
     * Add GET requests to needed routes
     *
     * @param string $path
     * @param string $action
     * @return void
     */
    public static function get(string $path, string $action)
    {
        self::$routes[] = new self('get', $path, $action);
    }

    /**
     * Add POST requests to needed routes
     *
     * @param string $path
     * @param string $action
     * @return void
     */
    public static function post(string $path, string $action)
    {
        self::$routes[] = new self('post', $path, $action);
    }

    /**
     * Get routes array
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Handle route request
     *
     * @param string $path
     * @return void
     */
    public static function handleRequest(string $path)
    {
        $desired_route = null;

        /** @var Route $route */
        foreach (self::$routes as $route) {

            $pattern = $route->getPath();

            $pattern = str_replace('/', '\/', $pattern);

            $pattern = '/^' . $pattern . '$/i';
            $pattern = preg_replace('/{[A-Za-z0-9]+}/', '([A-Za-z0-9]+)', $pattern);

            if (preg_match($pattern, $path, $match)) {
                $desired_route = $route;
            }
        }

        if ($desired_route === null) {
            abort();
        }

        $url_parts = explode('/', $path);
        $route_parts = explode('/', $desired_route->path);

        foreach ($route_parts as $key => $value) {
            if (!empty($value)) {
                $value = str_replace('{', '', $value, $count1);
                $value = str_replace('}', '', $value, $count2);

                if ($count1 == 1 && $count2 == 1) {
                    Params::set($value, $url_parts[$key]);
                }
            }
        }

        if ($desired_route) {
            if ($desired_route->method != strtolower($_SERVER['REQUEST_METHOD'])) {
                http_response_code(404);

                echo '<h1>Route Not Allowed</h1>';

                die();
            } else {
                $actions = explode('@', $desired_route->action);

                $class = '\\App\\Controllers\\' . $actions[0];

                $obj = new $class();
                echo call_user_func(array($obj, $actions[1]));
            }

        } else {
            http_response_code(404);

            echo '<h1>404 - Not Found</h1>';

            die();
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function setPath(string $path): Route
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Route
     */
    public function setMethod(string $method): Route
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Route
     */
    public function setAction(string $action): Route
    {
        $this->action = $action;

        return $this;
    }
}