<?php

namespace System\Session\Handler;

use System\Session,
    Predis\Client as PredisClient;

/**
 * Description of RedisSessionHandler
 *
 * @author root
 */
class RedisSessionHandler implements \SessionHandlerInterface
{

    /**
     *
     * @var PredisClient 
     */
    protected $db;
    protected $ttl;
    protected $prefix = '';
    public static $salt = '67d2d5eeae1';
    protected $writeEmptyValue = false;

    public function __construct(PredisClient $db, $prefix = '', $writeEmptyValue = false)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->ttl = Session::getRememberMeTime();
        $this->writeEmptyValue = $writeEmptyValue;
        Session::disableGarbageCollection();
    }

    public function create_sid()
    {
        return md5(microtime() . self::$salt);
    }

    public function open($savePath, $sessionName)
    {
        // No action necessary because connection is injected
        // in constructor and arguments are not applicable.
        return true;
    }

    public function read($id)
    {
        $id = $this->prefix . $id;
        
        $value = $this->db->get($id);
        
        if (!is_string($value)) {
            return '';
        }
        
        return $value;
    }

    public function write($id, $data)
    {
        $id = $this->prefix . $id;

        if (!$this->writeEmptyValue && strlen($data) === 0) {
            $this->db->del($this->prefix . $id);
        } else {
            $this->db->set($id, $data);
            $this->db->expire($id, $this->ttl);
        }
        
        return true;
    }

    public function destroy($id)
    {
        $this->db->del($this->prefix . $id);
        
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
