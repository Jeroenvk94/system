<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    public static $get = array(
        'a' => 1,
        'b' => 2
    );
    public static $post = array(
        'a' => 'value',
        'b' => array(5)
    );
    public static $cookie = array(
        'key1' => 'value1',
        'key2' => 'value2'
    );
    public static $server = array(
        'REQUEST_METHOD' => 'POST',
        'HTTPS' => 'on',
        'HTTP_HOST' => 'localhost',
        'REQUEST_URI' => '/test.php',
        'HTTP_X_REQUESTED_WITH' => 'XMLHTTPRequest'
    );

    /**
     *
     * @var \System\Request 
     */
    protected static $request;

    public static function setUpBeforeClass()
    {
        self::$request = new \System\Request(self::$get, self::$post, self::$cookie, self::$server);
    }

    public function testGet1()
    {
        $this->assertSame(self::$get, self::$request->get());
        $this->assertSame(self::$get['b'], self::$request->get('b'));
        $this->assertNull(self::$request->get('c'));
    }

    public function testPost()
    {
        $this->assertSame(self::$post, self::$request->post());
        $this->assertSame(self::$post['b'], self::$request->post('b'));
        $this->assertNull(self::$request->post('c'));
    }

    public function testCookie()
    {
        $this->assertSame(self::$cookie, self::$request->cookie());
        $this->assertSame(self::$cookie['key2'], self::$request->cookie('key2'));
        $this->assertNull(self::$request->cookie('key3'));
    }

    public function testServer()
    {
        $this->assertSame(self::$server, self::$request->server());
        $this->assertSame(self::$server['REQUEST_METHOD'], self::$request->server('REQUEST_METHOD'));
        $this->assertNull(self::$request->server('someKey'));
    }

    public function testIsSecure()
    {
        $secure = self::$request->isSecure();
        $this->assertTrue($secure);
    }

    public function testUri()
    {
        $uri = self::$request->uri();
        $this->assertSame($uri, self::$server['REQUEST_URI']);
    }

    public function testHelpMethods()
    {
        $this->assertFalse(self::$request->isCli());
        $this->assertTrue(self::$request->isAjax());
        $this->assertFalse(self::$request->isGet());
        $this->assertTrue(self::$request->isPost());
        $this->assertFalse(self::$request->isPut());
        $this->assertFalse(self::$request->isDelete());
        $this->assertFalse(self::$request->isHead());
        $this->assertFalse(self::$request->isOptions());
        $this->assertSame('https', self::$request->scheme());
        $this->assertFalse(self::$request->isMobile());
    }

    public function testMethod()
    {
        $request = new \System\Request(array('_method' => 'DELETE'), self::$post, self::$cookie, self::$server);
        $isDelete = $request->isDelete();
        $this->assertTrue($isDelete);
    }

    public function testHasMethods()
    {
        $this->assertTrue(self::$request->hasGet('a', 'b'));
        $this->assertFalse(self::$request->hasGet('a', 'b', 'c'));
        
        $this->assertTrue(self::$request->hasPost('a', 'b'));
        $this->assertFalse(self::$request->hasPost('a', 'c'));
        
        $this->assertTrue(self::$request->hasCookie('key1'));
        $this->assertFalse(self::$request->hasCookie('key10'));
        
        $this->assertTrue(self::$request->hasServer('REQUEST_METHOD', 'HTTP_HOST'));
        $this->assertFalse(self::$request->hasServer('a', 'c'));
    }

}
