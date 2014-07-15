<?php

namespace System;

/**
 * Description of DI
 *
 * @author Rastor
 */
class DI implements \ArrayAccess {

    private static $_container = array();
    private static $_shared = array();
    private static $_keys = array();

    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetExists($offset) {
        return isset(self::$_keys[$offset]);
    }

    public function offsetUnset($offset) {
        unset(self::$_keys[$offset], self::$_container[$offset], self::$_shared[$offset]);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function setShared($offset, $callable) {
        if (is_callable($callable)) {
            self::$_shared[$offset] = $callable;
            self::$_keys[$offset] = true;
        } else {
            throw new \Exception('Value must be callable!');
        }
    }

    public function set($offset, $value) {
        if (is_null($offset)) {
            throw new \Exception('Invalid offset!');
        } else {
            self::$_keys[$offset] = true;
            self::$_container[$offset] = $value;
        }
    }

    public function get($offset) {
        if (isset(self::$_keys[$offset])) {
            if (isset(self::$_shared[$offset])) {
                $f = self::$_shared[$offset];
                self::$_container[$offset] = $f();
                unset(self::$_shared[$offset]);
                return self::$_container[$offset];
            } elseif (isset(self::$_container[$offset])) {
                return self::$_container[$offset];
            }
        }

        return null;
    }

}
