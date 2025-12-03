<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    public function get(string $uri, array $handler): void
    {
        $this->routes['GET'][$uri] = $handler;
    }
    
    public function post(string $uri, array $handler): void
    {
        $this->routes['POST'][$uri] = $handler;
    }
    
    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        if (!isset($this->routes[$method][$path]))
        {
            http_response_code(404);
            include __DIR__ . '/../Views/404.php';
            return;
        }
        
        [$controllerClass, $methodName] = $this->routes[$method][$path];
        
        $controller = new $controllerClass;
        
        $controller->$methodName();
        
    }
}