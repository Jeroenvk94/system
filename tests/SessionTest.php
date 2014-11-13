<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{

    public $session;

    public static function setUpBeforeClass()
    {
        \System\Session::$isUnitTesting = true;
    }

    protected function setUp()
    {
        $this->session = new \System\Session();
    }

    public function testSet()
    {
        $this->session['key'] = 'value';
        $this->assertSame('value', $this->session['key']);
        $this->assertTrue(isset($this->session['key']));
        $this->assertTrue(\System\Session::isStarted());

        unset($this->session['key']);
        $this->assertFalse(isset($this->session['key']));

        $this->assertNotNull(\System\Session::getId());
    }

    public function testConfig()
    {
        \System\Session::setConfig(array(
            'cookieParams' => array(
                'lifetime' => 10,
                'path' => '/',
                'domain' => 'domain.com',
                'secure' => true,
                'httpOnly' => true
            ),
            'name' => 'myName',
            'rememberMeTime' => 1000
        ));

        $this->assertSame(array(
            'lifetime' => 10,
            'path' => '/',
            'domain' => 'domain.com',
            'secure' => true,
            'httponly' => true
                ), session_get_cookie_params());

        \System\Session::setName();
        $this->assertSame('myName', $this->session->getName());

        \System\Session::setName('newName');
        $this->assertSame('newName', $this->session->getName());

        \System\Session::rememberMe(100);
        $params = session_get_cookie_params();
        $this->assertSame(100, $params['lifetime']);

        \System\Session::rememberMe();
        $params = session_get_cookie_params();
        $this->assertSame(1000, $params['lifetime']);

        \System\Session::forgotMe();
        $params = session_get_cookie_params();
        $this->assertSame(0, $params['lifetime']);
    }

}
