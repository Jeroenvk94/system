<?php

namespace System;

class FlashMessages
{

    const INFO = 0;
    const WARNING = 1;
    const ERROR = 2;
    const SUCCESS = 3;

    private $sessionKey = '_flashMessages';
    private $sessionHandler = null;
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

    public function __construct(\System\Session $session)
    {
        $this->sessionHandler = $session;

        if (!isset($this->sessionHandler[$this->sessionKey])) {
            $this->clear();
        }
    }

    public function clear()
    {
        $this->sessionHandler[$this->sessionKey] = array();
    }

    public function add($type, $message)
    {
        array_push($this->sessionHandler[$this->sessionKey], array(
            'type' => (int) $type,
            'message' => $message
        ));
    }

    public function hasData()
    {
        return count($this->sessionHandler[$this->sessionKey]) > 0;
    }

    public function setStyles(array $styles)
    {
        $this->styles = $styles;
    }

    public function getData()
    {
        $result = array();
        foreach ($this->sessionHandler[$this->sessionKey] as $item) {
            $result[] = array(
                'message' => $item['message'],
                'styles' => $this->styles[$item['type']]
            );
        }
        $this->clear();
        return $result;
    }

}
