<?php
namespace Application;

class Router
{
    protected $routes;

    public function __construct($routeArray)
    {
        $this->routes = $routeArray;
    }

    public function findRoute($url)
    {
        $urlContent = explode('?', $url);
        $urlPath = $urlContent[0];
        foreach ($this->routes as $route) {
            $path = preg_replace('/{.*?}/', '.*?', $route['path']);
            if (preg_match('#^'.$path.'$#', $urlPath)) {
                return $route;
            }
        }

        return null;
    }

    public function hasRoute($name)
    {
        return isset($this->routes[$name]);
    }

    public function generateRoute($name, $params = [])
    {
        if (!$this->hasRoute($name)) {
            throw new Exception("Route not found", 500);
        }
        $route = $this->routes[$name];
        $path = $route['path'];
        if (is_array($params) && !empty($params)) {
            foreach ($params as $key => $value) {
                $path = str_replace('{'.$key.'}', $value, $path);
            }
        }

        return $path;
    }
}
