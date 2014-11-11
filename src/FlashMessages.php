<?php

namespace System;

use System\Session;

class FlashMessages
{
    const INFO = 0;
    const WARNING = 1;
    const ERROR = 2;
    const SUCCESS = 3;

    private $di;
    private $sessionKey = '_flashMessages';
    private $session;
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
        )
    );

    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->session = $this->di->get('session');

        if (!($this->session instanceof Session)) {
            throw new DI\InvalidOffsetException("Session object not defined!");
        }

        if (!isset($this->session[$this->sessionKey])) {
            $this->clear();
        }
    }

    public function clear()
    {
        $this->session[$this->sessionKey] = array();
    }

    public function add($type, $message)
    {
        $data = $this->session[$this->sessionKey];

        array_push($data, array(
            'type' => (int) $type,
            'message' => $message
        ));

        $this->session[$this->sessionKey] = $data;
    }

    public function hasData()
    {
        return count($this->session[$this->sessionKey]) > 0;
    }

    public function setStyles(array $styles)
    {
        $this->styles = $styles;
    }

    public function getData()
    {
        $result = array();
        foreach ($this->session[$this->sessionKey] as $item) {
            $result[] = array(
                'message' => $item['message'],
                'styles' => $this->styles[$item['type']]
            );
        }
        $this->clear();
        return $result;
    }

}
