<?php

namespace App;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback,
        ];
    }

    public function dispatch()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


        if ($basePath !== '/') {
            $uri = str_replace($basePath, '', $uri);
        }


        $method = $_SERVER['REQUEST_METHOD'];


        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                return call_user_func($route['callback']);
            }
        }


        http_response_code(404);
        echo "Página no encontrada.";
    }

}
