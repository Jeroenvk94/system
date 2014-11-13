<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class FlashMessagesTest extends \PHPUnit_Framework_TestCase
{

    protected $flashMessages;
    protected $db = 'someDB';

    public static function setUpBeforeClass()
    {
        \System\Session::$isUnitTesting = true;
    }

    protected function setUp()
    {
        $di = new \System\DI();
        $di['session'] = new \System\Session();
        $this->flashMessages = new \System\FlashMessages($di);
    }

    public function testGetDb()
    {
        $has = $this->flashMessages->hasData();

        $this->assertFalse($has);
    }

    public function testAdd()
    {
        $this->flashMessages->add(\System\FlashMessages::SUCCESS, 'success message');
        $has = $this->flashMessages->hasData();

        $this->assertTrue($has);
    }

    public function testClear()
    {
        $this->flashMessages->add(\System\FlashMessages::SUCCESS, 'new message');
        $before = $this->flashMessages->hasData();
        $this->flashMessages->clear();
        $after = $this->flashMessages->hasData();

        $this->assertNotEquals($before, $after);
    }

    public function testSetStylesAndGetData()
    {
        $styles = array(
            \System\FlashMessages::INFO => array(
                'class' => 'info-class'
            )
        );

        $this->flashMessages->add(\System\FlashMessages::INFO, 'message');
        $this->flashMessages->setStyles($styles);
        $flashData = $this->flashMessages->getData();

        $this->assertEquals(array(
            array(
                'message' => 'message',
                'styles' => $styles[\System\FlashMessages::INFO],
            )
                ), $flashData);
    }

    public function testConstructorError()
    {
        $di = new \System\DI();
        
        try {
            new \System\FlashMessages($di);
        } catch (\System\DI\InvalidOffsetException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

}
