<?php
namespace System;

class FlashMessages
{

    const INFO = 1;
    const WARNING = 2;
    const ERROR = 3;
    const SUCCESS = 4;

    private $sessionKey = '_flashMessages';
    private $classes = array(
        self::INFO => 'alert alert-info',
        self::WARNING => 'alert alert-block',
        self::ERROR => 'alert alert-danger',
        self::SUCCESS => 'alert alert-success',
    );
    private $icons = array(
        self::INFO => 'fa fa-info-circle sign',
        self::WARNING => 'fa fa-warning sign',
        self::ERROR => 'fa fa-times-circle sign',
        self::SUCCESS => 'fa fa-check sign',
    );

    public function __construct()
    {
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

    public function setClasses($array)
    {
        $this->classes = $array;
    }

    public function __toString()
    {
        $result = '';
        foreach ($_SESSION[$this->sessionKey] as $item) {
            $result .= "<div class=\"{$this->classes[$item['type']]}\"><button type=\"button\" "
                    . "class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"
                    . "<i class=\"{$this->icons[$item['type']]}\"></i>{$item['message']}</div>";
        }
        $this->clear();

        return $result;
    }

    public function getViewData()
    {
        return (String) $this;
    }

    public function getData()
    {
        return $_SESSION[$this->sessionKey];
    }

}
