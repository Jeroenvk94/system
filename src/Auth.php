<?php
namespace System;

class Auth
{

    private $_authKey = null;

    public function __construct($name)
    {
        $this->_authKey = $name;
    }

    public function hasIdentity()
    {
        return isset($_SESSION[$this->_authKey]);
    }

    public function setIdentity($data)
    {
        $_SESSION[$this->_authKey] = $data;
    }

    public function getIdentity()
    {
        return $_SESSION[$this->_authKey];
    }

    public function clearIdentity()
    {
        unset($_SESSION[$this->_authKey]);
    }

}
