<?php declare(strict_types = 1);

if (!function_exists('route_register')) {
    function route_register(\Slim\App $app) : void
    {
        $config = $app->getContainer()->get(\App\Utility\Config::class);
        $routeSettings = $config->get('app.api_versions');

        foreach($routeSettings as $routeSetting) {
            $routeGroup = $app->group($routeSetting['prefix'] ?? '', function (\Slim\Routing\RouteCollectorProxy $route) use ($routeSetting){
                $routes = require base_path($routeSetting['file']);
                $routes($route);
            });

            if (isset($routeSetting['middlewares'])) {
                foreach ($routeSetting['middlewares'] ?? [] as $middleware) {
                    $routeGroup->add($middleware);
                }
            }
        }
    }
}

if (!function_exists('array_get')) {
    function array_get(array $data, ?string $node = '.', $default = null)
    {
        if (empty($node) || $node == '.' || is_null($node)) {
            return $data;
        }

        if (!$node) return $default;

        $terminate = false;
        $path = explode('.', $node);

        foreach ($path as $val) {
            if (!array_key_exists($val, $data)) {
                $terminate = true;
                break;
            }

            $data = &$data[$val];
        }

        if ($terminate) {
            return $default;
        }

        return $data;
    }
}

if (!function_exists('array_set')) {
    function array_set(array &$data, string $node, $value)
    {
        foreach (explode('.', $node) as $key) {
            if (!isset($data[$key]) || !is_array($data[$key])) {
                $data[$key] = [];
            }

            $data = &$data[$key];
        }

        $data = $value;
    }
}

if (!function_exists('base_path')) {
    function base_path(?string $path = null) : string
    {
        $basePath = rtrim(BASE_PATH, '\/') . DIRECTORY_SEPARATOR;

        if (!$path) {
            return $basePath;
        }

        return $basePath . ltrim($path, '\/');
    }
}

if (!function_exists('config_path')) {
    function config_path(?string $path = null) : string
    {
        $basePath = base_path('config' . DIRECTORY_SEPARATOR);

        if (!$path) {
            return $basePath;
        }

        return $basePath . ltrim($path, '\/');
    }
}

if (!function_exists('app_path')) {
    function app_path(?string $path = null) : string
    {
        $basePath = base_path('app' . DIRECTORY_SEPARATOR);

        if (!$path) {
            return $basePath;
        }

        return $basePath . ltrim($path, '\/');
    }
}

if (!function_exists('public_path')) {
    function public_path(?string $path = null) : string
    {
        $basePath = base_path('public' . DIRECTORY_SEPARATOR);

        if (!$path) {
            return $basePath;
        }

        return $basePath . ltrim($path, '\/');
    }
}

if (!function_exists('route_path')) {
    function route_path(?string $path = null) : string
    {
        $basePath = base_path('routes' . DIRECTORY_SEPARATOR);

        if (!$path) {
            return $basePath;
        }

        return $basePath . ltrim($path, '\/');
    }
}

if (!function_exists('env')) {
    function env(string $name, $default = null)
    {
        return $_ENV[$name] ?? $default;
    }
}
