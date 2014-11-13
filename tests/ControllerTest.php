<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $controller;

    protected function setUp()
    {
        $di = new \System\DI();
        $di->set('request', new \System\Request());
        $this->controller = $this->getMockForAbstractClass('\System\Controller', array($di));
        
    }

    public function testGetDI()
    {
        $this->assertInstanceOf('\System\DI', $this->controller->getDI());
    }
    
    public function testGetRequest()
    {
        $this->assertInstanceOf('\System\Request', $this->controller->getRequest());
    }

}
