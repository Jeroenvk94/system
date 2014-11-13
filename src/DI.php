<?php

namespace System;

/**
 * Description of DI
 *
 * @author Rastor
 */
class DI implements \ArrayAccess
{

    private $container = array();
    private $shared = array();
    private $keys = array();

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return isset($this->keys[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->keys[$offset], $this->container[$offset], $this->shared[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function setShared($offset, callable $callable)
    {
        $this->shared[$offset] = $callable;
        $this->keys[$offset] = true;
    }

    public function set($offset, $value)
    {
        if (!strlen($offset)) {
            throw new DI\InvalidOffsetException('Invalid offset!');
        }

        $this->keys[$offset] = true;
        $this->container[$offset] = $value;
    }

    public function get($offset)
    {
        if (!isset($this->keys[$offset])) {
            return null;
        }

        if (isset($this->shared[$offset])) {
            $constructor = $this->shared[$offset];
            $this->container[$offset] = $constructor();
            unset($this->shared[$offset]);
            return $this->container[$offset];
        }

        return $this->container[$offset];
    }

    public function exist($offset)
    {
        return $this->offsetExists($offset);
    }

    public function delete($offset)
    {
        $this->offsetUnset($offset);
    }

    /**
     * 
     * @return System\Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * 
     * @return System\Router
     */
    public function getRouter()
    {
        return $this->get('router');
    }

    /**
     * 
     * @return System\Translator
     */
    public function getTranslator()
    {
        return $this->get('t');
    }

    /**
     * 
     * @return System\Request
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * 
     * @return System\Auth
     */
    public function getAuth()
    {
        return $this->get('auth');
    }
    
    /**
     * 
     * @return System\FlashMessages
     */
    public function getFlashMessages()
    {
        return $this->get('flashMessages');
    }

}
