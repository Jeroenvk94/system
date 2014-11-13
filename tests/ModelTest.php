<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{

    protected $model;
    protected $db = 'someDB';

    protected function setUp()
    {
        $di = new \System\DI();
        $di->set('db', $this->db);
        
        $this->model = $this->getMockForAbstractClass('\System\Model', array($di));
    }

    public function testGetDb()
    {
        $db = $this->model->getDb();
        $this->assertEquals($this->db, $db);
    }
    
    public function testGetDI()
    {
        $this->assertInstanceOf('\System\DI', $this->model->getDI());
    }

}
