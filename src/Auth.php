<?php
namespace System;

class Auth
{

    private $authKey = null;

    public function __construct($name)
    {
        $this->authKey = $name;
    }

    public function hasIdentity()
    {
        return isset($_SESSION[$this->authKey]);
    }

    public function setIdentity($data)
    {
        $_SESSION[$this->authKey] = $data;
    }

    public function getIdentity()
    {
        return $_SESSION[$this->authKey];
    }

    public function clearIdentity()
    {
        unset($_SESSION[$this->authKey]);
    }
}
