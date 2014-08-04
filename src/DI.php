<?php

namespace System;

/**
 * Description of DI
 *
 * @author Rastor
 */
class DI implements \ArrayAccess
{

    private static $container = array();
    private static $shared = array();
    private static $keys = array();

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return isset(self::$keys[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset(self::$keys[$offset], self::$container[$offset], self::$shared[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function setShared($offset, $callable)
    {
        if (is_null($offset)) {
            throw new \Exception('Invalid offset!');
        }

        if (!is_callable($callable)) {
            throw new \Exception('Value must be callable!');
        }

        self::$shared[$offset] = $callable;
        self::$keys[$offset] = true;
    }

    public function set($offset, $value)
    {
        if (is_null($offset)) {
            throw new \Exception('Invalid offset!');
        }

        self::$keys[$offset] = true;
        self::$container[$offset] = $value;
    }

    public function get($offset)
    {
        if (isset(self::$keys[$offset])) {
            if (isset(self::$shared[$offset])) {
                $constructor = self::$shared[$offset];
                self::$container[$offset] = $constructor();
                unset(self::$shared[$offset]);
                return self::$container[$offset];
            } elseif (isset(self::$container[$offset])) {
                return self::$container[$offset];
            }
        }

        return null;
    }

}
