<?php
namespace System;

abstract class Model
{

    /**
     *
     * @var DI
     */
    public $di;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * 
     * @return \PDO
     */
    public function getDb()
    {
        return $this->di['db'];
    }
}
