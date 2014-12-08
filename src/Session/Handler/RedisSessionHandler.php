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
    static $salt = '';

    public function __construct(PredisClient $db, $prefix = '')
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->ttl = Session::getRememberMeTime();
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
        $sessData = $this->db->get($id);
        $this->db->expire($id, $this->ttl);
        return $sessData;
    }

    public function write($id, $data)
    {
        $id = $this->prefix . $id;
        $this->db->set($id, $data);
        $this->db->expire($id, $this->ttl);
    }

    public function destroy($id)
    {
        $this->db->del($this->prefix . $id);
    }

    public function gc($maxLifetime)
    {
        // no action necessary because using EXPIRE
        return true;
    }

    public function close()
    {
        $this->db = null;
        unset($this->db);
    }

}
