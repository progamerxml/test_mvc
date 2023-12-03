<?php

namespace PRGANYAR\MVC\TEST\App;

class Route
{
    private static $routes = [];

    public static function add(string $method, string $url, string $controller, string $function, array $middleware): void
    {
        self::$routes[] = [
            "method" => $method,
            "url" => $url,
            "controller" => $controller,
            "function" => $function,
            "middleware" => $middleware
        ];
    }

    public static function gas(): void
    {
        $url = "/";
        if(isset($_SERVER['PATH_INFO']))
        {
            $url = $_SERVER['PATH_INFO'];
        }
        $method = $_SERVER['REQUEST_METHOD'];

        foreach(self::$routes as $route)
        {

            $pattern = "#^" . $route['url'] . "$#";
            if(preg_match($pattern, $url, $variables) && $route['method'] == $method)
            {
                foreach($route['middleware'] as $middleware)
                {
                    $instance = new $middleware;
                    $instance->cek();
                }

                $function = $route['function'];
                $controller = new $route['controller'];

                array_shift($variables);

                call_user_func_array([$controller, $function], $variables);

                return;
            }
        }

        http_response_code(404);
        View::redirect('/error/404');
    }
}
