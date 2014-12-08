<?php

namespace System;

/**
 * Session class
 *
 * @author Rastor
 */
class Session implements \ArrayAccess
{

    private static $cookieParams = array(
        'lifetime' => 0,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httpOnly' => false
    );
    private static $rememberMeTime = 1209600; // 2 weeks
    private static $started = false;
    private static $name = 'PHPSESSID';
    private static $gcDisabled = false;
    public static $isUnitTesting = false;

    public static function setConfig($config)
    {
        if (isset($config['cookieParams'])) {
            self::setCookieParams($config['cookieParams']);
        }

        if (isset($config['name'])) {
            self::$name = $config['name'];
        }

        if (isset($config['rememberMeTime'])) {
            self::setRememberMeTime($config['rememberMeTime']);
        }
    }

    public static function disableGarbageCollection()
    {
        self::$gcDisabled = true;
        return ini_set('session.gc_probability', 0);
    }

    public static function start()
    {
        if (!self::$started) {
            if (session_status() === PHP_SESSION_DISABLED) {
                throw new Session\SessionDisabledException("Sessions is currently disabled on your system");
            }

            self::setName();
            self::setMaxLifeTime();

            if (!self::$isUnitTesting && !session_start()) {
                throw new Session\SessionStartException("Error during session initialization");
            }

            self::$started = true;
        }
    }

    public static function setMaxLifeTime($time = 0)
    {
        if (self::$gcDisabled) {
            return;
        }

        if ($time < static::$rememberMeTime) {
            $time = static::$rememberMeTime;
        }

        return ini_set('session.gc_maxlifetime', $time);
    }

    public static function setCookieParams($params = null)
    {
        if (!empty($params)) {
            self::$cookieParams = array_merge(self::$cookieParams, $params);
        }

        session_set_cookie_params(
                self::$cookieParams['lifetime'], self::$cookieParams['path'], self::$cookieParams['domain'], self::$cookieParams['secure'], self::$cookieParams['httpOnly']
        );
    }

    public static function setRememberMeTime($time = 0)
    {
        self::$rememberMeTime = $time;
        self::setMaxLifeTime();
    }

    public static function getName()
    {
        return session_name();
    }

    public static function setName($name = '')
    {
        if (!empty($name)) {
            self::$name = $name;
        }

        session_name(self::$name);
    }

    public static function getId()
    {
        return session_id();
    }

    public static function destroy()
    {
        if (!session_destroy()) {
            throw new Session\SessionDestroyException("Error during session destroy");
        }
    }

    public static function rememberMe($seconds = 0)
    {
        if ($seconds == 0) {
            $seconds = self::$rememberMeTime;
        }

        self::setCookieParams(array('lifetime' => $seconds));
        self::regenerateId();
    }

    public static function forgotMe()
    {
        self::setCookieParams(array('lifetime' => 0));
        self::regenerateId();
    }

    public static function regenerateId($clear = false)
    {
        if (!self::$isUnitTesting && !session_regenerate_id($clear)) {
            throw new Session\SessionRestartException("Error during session reinitialization");
        }
    }

    public static function isStarted()
    {
        return self::$started;
    }

    public static function setSessionHandler(SessionHandlerInterface $sessionHandler)
    {
        session_set_save_handler($sessionHandler);
    }

    public static function getRememberMeTime()
    {
        return self::$rememberMeTime;
    }

    public function offsetSet($offset, $value)
    {
        if (!self::$started) {
            self::start();
        }

        $_SESSION[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        if (!self::$started) {
            self::start();
        }

        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!self::$started) {
            self::start();
        }

        return isset($_SESSION[$offset]) ? $_SESSION[$offset] : null;
    }

    public function offsetUnset($offset)
    {
        if (!self::$started) {
            self::start();
        }

        unset($_SESSION[$offset]);
    }

}
