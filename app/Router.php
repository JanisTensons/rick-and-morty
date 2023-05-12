<?php declare(strict_types=1);

namespace App;

class Router
{
    public static function response(): ?View
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', 'App\Controllers\CharacterController@getIndex');
            $r->addRoute('GET', '/characters', 'App\Controllers\CharacterController@getCharacters');
            $r->addRoute('GET', '/characters-by-name', 'App\Controllers\CharacterController@getByName');
            $r->addRoute('GET', '/characters-by-episode', 'App\Controllers\CharacterController@getByEpisode');
            $r->addRoute('GET', '/locations', 'App\Controllers\CharacterController@getLocations');
            $r->addRoute('GET', '/characters-by-location', 'App\Controllers\CharacterController@getByLocation');
        });

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                return null;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                return null;

            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = explode('@', $handler);
                $controller = new $controllerName;
                /** @var View $response */
                $response = $controller->{$methodName}();
                return $response;
        }
        return null;
    }
}