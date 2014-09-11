<?php

namespace System;

use System\Session;

class FlashMessages
{

    const INFO = 0;
    const WARNING = 1;
    const ERROR = 2;
    const SUCCESS = 3;

    private $sessionKey = '_flashMessages';
    private $styles = array(
        self::INFO => array(
            'block' => 'alert alert-info',
            'icon' => 'fa fa-info-circle'
        ),
        self::WARNING => array(
            'block' => 'alert alert-block',
            'icon' => 'fa fa-warning'
        ),
        self::ERROR => array(
            'block' => 'alert alert-danger',
            'icon' => 'fa fa-times-circle'
        ),
        self::SUCCESS => array(
            'block' => 'alert alert-success',
            'icon' => 'fa fa-check sign'
        ),
    );

    public function __construct()
    {
        Session::start();

        if (!isset($_SESSION[$this->sessionKey])) {
            $this->clear();
        }
    }

    public function clear()
    {
        $_SESSION[$this->sessionKey] = array();
    }

    public function add($type, $message)
    {
        $_SESSION[$this->sessionKey][] = array(
            'type' => (int) $type,
            'message' => $message
        );
    }

    public function hasData()
    {
        return count($_SESSION[$this->sessionKey]) > 0;
    }

    public function setStyles(array $styles)
    {
        $this->styles = $styles;
    }

    public function getData()
    {
        $result = array();
        foreach ($_SESSION[$this->sessionKey] as $item) {
            $result[] = array(
                'message' => $item['message'],
                'styles' => $this->styles[$item['type']]
            );
        }
        $this->clear();
        return $result;
    }

}