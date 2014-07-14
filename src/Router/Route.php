<?php

namespace System\Router;

class Route {

    public $pattern;
    public $target;

    public function __construct($pattern, $target = null) {
        $this->pattern = $pattern;
        $this->target = $target;
    }

    public function isMatch($uri) {
        return $this->pattern === $uri;
    }

    public function getUrl() {
        return $this->pattern;
    }

}
