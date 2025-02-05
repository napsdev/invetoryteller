<?php

namespace App;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $pathRegex = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^/]+)', $path);
        $pathRegex = str_replace('/', '\/', $pathRegex);
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'regex' => "/^{$pathRegex}$/",
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
            if ($route['method'] === $method && preg_match($route['regex'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['callback'], $params);
            }
        }

        http_response_code(404);
        echo "Página no encontrada.";
    }
}
