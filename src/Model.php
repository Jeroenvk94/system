<?php

namespace System;

use PDO;

abstract class Model
{

    /**
     *
     * @var DI
     */
    protected $di;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * 
     * @return PDO
     */
    public function getDb()
    {
        return $this->di->get('db');
    }
}
