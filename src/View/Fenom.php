<?php

namespace System\View;

/**
 * View adapter for Fenom template engine
 * 
 * Fenom on github: https://github.com/bzick/fenom
 *
 * @author Orest
 */
class Fenom implements \System\View {

    protected $_fenom;

    public function __construct(\Fenom $fenom) {
        $this->_fenom = $fenom;
    }

    public function display($template, $vars = array()) {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }

        $this->_fenom->display($template, $vars);
    }

    public function fetch($template, $vars = array()) {
        if (!is_array($vars)) {
            $vars = (Array) $vars;
        }

        return $this->_fenom->fetch($template, $vars);
    }
}
