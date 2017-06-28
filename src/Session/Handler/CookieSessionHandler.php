<?php

namespace System\Session\Handler;

use System\Session,
    System\Request;

/**
 * Description of CookieSessionHandler
 *
 * @author root
 */
class CookieSessionHandler implements \SessionHandlerInterface
{

    protected $ttl;
    protected $key = 'session';
    protected $salt = 'e849acf737';
    protected $sidSalt = '';
    protected $writeEmptyValue = false;
    protected $request;

    public function __construct(Request $request, $key = 'session', $salt = null, $sidSalt = null)
    {
        $this->ttl = Session::getRememberMeTime();
        $this->request = $request;
        $this->key = $key;

        if (strlen($salt)) {
            $this->salt = $salt;
        }
        
        if (strlen($sidSalt)) {
            $this->sidSalt = $sidSalt;
        }

        Session::disableGarbageCollection();
    }

    public function create_sid()
    {
        return md5(microtime() . $this->sidSalt);
    }

    public function open($savePath, $sessionName)
    {
        // No action necessary because connection is injected
        // in constructor and arguments are not applicable.
        return true;
    }

    public function read($id)
    {
        if ($this->request->hasCookie($this->key)) {
            $value = $this->request->cookie($this->key);

            return $this->getStoredData($id, $value);
        }

        return null;
    }

    public function write($id, $data)
    {
        $raw = serialize($data);
        $value = $raw . $this->getHashValue($id, $raw);

        setcookie($this->key, base64_encode($value), time() + $this->ttl, '/');
        
        return true;
    }

    private function getHashValue($id, $data)
    {
        return md5($id . $data . $this->salt);
    }

    private function getStoredData($id, $value)
    {
        if (!strlen($value)) {
            return null;
        }

        $hashLength = 32;

        $raw = base64_decode($value);

        if (strlen($raw) < $hashLength) {
            return null;
        }

        $hash = substr($raw, strlen($raw) - $hashLength, $hashLength);
        $data = substr($raw, 0, -$hashLength);

        if ($this->getHashValue($id, $data) !== $hash) {
            return null;
        }

        return unserialize($data);
    }

    public function destroy($id)
    {
        setcookie($this->key, '', time());
        
        return true;
    }

    public function gc($maxLifetime)
    {
        // no action necessary because using EXPIRE
        return true;
    }

    public function close()
    {
        return true;
    }

}
