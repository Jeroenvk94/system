<?php

namespace System\Router;

class Route implements \System\Route {

    public $pattern;
    public $target;

    public function __construct($pattern, $target = null) {
        $this->pattern = $pattern;
        $this->target = $target;
    }

    public function isMatch($uri) {
        return $this->pattern === $uri;
    }

    public function getUri() {
        return $this->pattern;
    }

}
