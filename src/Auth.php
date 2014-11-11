<?php

namespace System;

use System\Session;

class Auth
{

    /**
     *
     * @var DI
     */
    private $di;
    private $authKey = null;
    private $session = null;

    public function __construct(DI $di, $name = 'auth')
    {
        $this->di = $di;
        $this->authKey = $name;
        $this->session = $this->di->get('session');

        if (!($this->session instanceof Session)) {
            throw new DI\InvalidOffsetException("Session object not defined!");
        }
    }

    /**
     * 
     * @return DI
     */
    public function getDI()
    {
        return $this->di;
    }

    public function hasIdentity()
    {
        return isset($this->session[$this->authKey]);
    }

    public function setIdentity($data)
    {
        $this->session[$this->authKey] = $data;
    }

    public function getIdentity()
    {
        return $this->session[$this->authKey];
    }

    public function clearIdentity()
    {
        unset($this->session[$this->authKey]);
    }

}
