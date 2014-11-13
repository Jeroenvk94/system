<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class AuthTest extends \PHPUnit_Framework_TestCase
{

    protected $auth;

    public static function setUpBeforeClass()
    {
        \System\Session::$isUnitTesting = true;
    }

    protected function setUp()
    {
        $di = new \System\DI();
        $di['session'] = new \System\Session();
        $this->auth = new \System\Auth($di);
    }

    public function testHasIdentity()
    {
        $this->assertFalse($this->auth->hasIdentity());
        
        $this->auth->setIdentity(1);
        $this->assertTrue($this->auth->hasIdentity());
    }
    
    public function testSetIdentity()
    {
        $data = array(
            'value1',
            'array1' => array(
                'key3' => 3
            )
        );
        $this->auth->setIdentity($data);

        $this->assertEquals($data, $this->auth->getIdentity());
    }
    
    public function testClearIdentity()
    {
        $this->auth->setIdentity(1);
        $this->auth->clearIdentity();
        
        $this->assertFalse($this->auth->hasIdentity());
    }
    
    public function testGetDi()
    {
        $this->assertInstanceOf('\System\DI', $this->auth->getDI());
    }
    
    public function testConstructorError()
    {
        $di = new \System\DI();
        
        try {
            new \System\Auth($di);
        } catch (\System\DI\InvalidOffsetException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

}
