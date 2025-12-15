<?php

/**
 * Router Class
 */

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Chuẩn hóa URI
        $uri = rtrim($uri, '/') ?:  '/';

        // Tìm route phù hợp
        foreach ($this->routes[$method] ??  [] as $pattern => $handler) {
            $regex = $this->convertToRegex($pattern);

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);

                list($controllerName, $methodName) = explode('@', $handler);

                // Xử lý admin controllers
                if (strpos($controllerName, 'admin/') === 0) {
                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
                    $controllerName = basename($controllerName);
                } else {
                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName .  '.php';
                }

                if (! file_exists($controllerFile)) {
                    $this->notFound("Controller not found: " . $controllerFile);
                }

                require_once $controllerFile;

                $controller = new $controllerName();

                if (! method_exists($controller, $methodName)) {
                    $this->notFound("Method not found:  " . $methodName);
                }

                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        $this->notFound();
    }

    private function convertToRegex($pattern)
    {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([0-9]+)', $pattern);
        $pattern = preg_replace('/\{([a-zA-Z]+):any\}/', '([^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    private function notFound($message = '')
    {
        http_response_code(404);
        if (APP_DEBUG && $message) {
            echo "<h1>404 Not Found</h1><p>$message</p>";
            echo "<p>URI: " . $_SERVER['REQUEST_URI'] . "</p>";
        } else {
            require_once __DIR__ . '/../views/errors/404.php';
        }
        exit;
    }
}
