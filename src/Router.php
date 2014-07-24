<?php
namespace System;

class Router
{

    protected $routes = array();
    protected $baseUrl = '';
    protected $routeName = null;
    protected $findRoute = false;

    public function add($name, \System\Route $route)
    {
        if (is_null($name)) {
            $this->routes[] = $route;
        } else {
            $this->routes[$name] = $route;
        }
    }

    public function setBaseUrl($value)
    {
        $this->baseUrl = $value;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function execute($uri, $targetHandler, $set = true)
    {
        if (is_callable($targetHandler)) {
            foreach ($this->routes as $name => $route) {
                if ($route->isMatch($uri)) {
                    if ($set) {
                        $this->routeName = $name;
                        $this->findRoute = $route;
                    }

                    return $targetHandler($route, $name);
                }
            }
            return $targetHandler(false, null);
        } else {
            throw new \Exception('Target Handler must be callable!');
        }
    }

    public function getFindRoute()
    {
        return $this->findRoute;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getRouteUrl($routeName, $parameters = null)
    {
        $uri = $this->getRouteUri($routeName, $parameters);
        if ($uri !== false) {
            return $this->baseUrl . $uri;
        }

        return false;
    }

    public function getRouteUri($routeName, $parameters = null)
    {
        if (isset($this->routes[$routeName])) {
            if (is_array($parameters)) {
                return $this->routes[$routeName]->getUri($parameters);
            }
            return $this->routes[$routeName]->getUri();
        }

        return false;
    }
}
