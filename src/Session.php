<?php

namespace System;

/**
 * Session class
 *
 * @author Rastor
 */
class Session implements \ArrayAccess
{

    private $cookieParams = array(
        'lifetime' => 0,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httpOnly' => false
    );
    private $saveHandler = null;
    private $started = false;
    private $name = 'PHPSESSID';

    public function __construct($config)
    {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new Session\SessionDisabledException("Sessions is currently disabled on your system");
        }

        if (isset($config['saveHandler'])) {
            $this->saveHandler = $config['saveHandler'];
        }

        if (isset($config['name'])) {
            $this->name = $config['name'];
        }

        if (isset($config['cookieParams'])) {
            $this->setCookieParams($config['cookieParams']);
        }
    }

    private function start()
    {
        session_name($this->name);
        
        if (isset($this->saveHandler)) {
            session_set_save_handler($this->saveHandler);
        }

        if (!session_start()) {
            throw new Session\SessionStartException("Error during session initialization");
        }

        $this->started = true;
    }

    public function setCookieParams($params = null)
    {
        if (!empty($params)) {
            $this->cookieParams = array_merge($this->cookieParams, $params);
        }
        
        session_set_cookie_params($this->cookieParams['lifetime'], $this->cookieParams['path'], $this->cookieParams['domain'], $this->cookieParams['secure'], $this->cookieParams['httpOnly']);
    }

    public function restart()
    {
        if (!session_regenerate_id()) {
            throw new Session\SessionRestartException("Error during session reinitialization");
        }
    }

    public function destroy()
    {
        if (!session_destroy()) {
            throw new Session\SessionDestroyException("Error during session destroy");
        }
    }

    public function offsetSet($offset, $value)
    {
        if (!$this->started) {
            $this->start();
        }

        $_SESSION[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        if (!$this->started) {
            $this->start();
        }

        return isset($_SESSION[$offset]);
    }

    public function offsetUnset($offset)
    {
        if (!$this->started) {
            $this->start();
        }

        unset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!$this->started) {
            $this->start();
        }

        return $_SESSION[$offset];
    }

}
