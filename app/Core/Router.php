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

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $handler) {
                preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $routePath, $paramNames);
                $paramNames = $paramNames[1];

                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);
                $pattern = "~^" . $pattern . "$~";

                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches);

                    $request = new Request();
                    $request->params = array_combine($paramNames, $matches) ?: [];

                    [$controllerClass, $methodName] = $handler;
                    $controller = new $controllerClass();

                    $controller->$methodName($request);
                    return;
                }
            }
        }

        http_response_code(404);
        include __DIR__ . '/../Views/404.php';
    }
}
