<?php

namespace System;

class Auth
{

    private $authKey = null;
    private $sessionHandler = null;

    public function __construct(\System\Session $sessionHandler, $name = 'auth')
    {
        $this->sessionHandler = $sessionHandler;
        $this->authKey = $name;
    }

    public function hasIdentity()
    {
        return isset($this->sessionHandler[$this->authKey]);
    }

    public function setIdentity($data)
    {
        $this->sessionHandler[$this->authKey] = $data;
    }

    public function getIdentity()
    {
        return $this->sessionHandler[$this->authKey];
    }

    public function clearIdentity()
    {
        unset($this->sessionHandler[$this->authKey]);
    }

}
