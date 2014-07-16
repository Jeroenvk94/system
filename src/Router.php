<?php

namespace System;

class Router {

    protected $_routes = array();
    protected $_baseUrl = '';
    protected $_routeName = null;
    protected $_findRoute = false;

    public function add($name, \System\Route $route) {
        if (is_null($name)) {
            $this->_routes[] = $route;
        } else {
            $this->_routes[$name] = $route;
        }
    }

    public function setBaseUrl($value) {
        $this->_baseUrl = $value;
    }

    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    public function execute($uri, $targetHandler, $set = true) {
        if (is_callable($targetHandler)) {
            foreach ($this->_routes as $name => $route) {
                if ($route->isMatch($uri)) {
                    if ($set) {
                        $this->_routeName = $name;
                        $this->_findRoute = $route;
                    }

                    return $targetHandler($route, $name);
                }
            }
            return $targetHandler(false, null);
        } else {
            throw new \Exception('Target Handler must be callable!');
        }
    }

    public function getFindRoute() {
        return $this->_findRoute;
    }

    public function getRouteName() {
        return $this->_routeName;
    }

    public function getRouteUrl($routeName, $parameters = null) {
        $uri = $this->getRouteUri($routeName, $parameters);
        if ($uri !== false) {
            return $this->_baseUrl . $uri;
        }

        return false;
    }

    public function getRouteUri($routeName, $parameters = null) {
        if (isset($this->_routes[$routeName])) {
            if (is_array($parameters)) {
                return $this->_routes[$routeName]->getUri($parameters);
            }
            return $this->_routes[$routeName]->getUri();
        }

        return false;
    }

}