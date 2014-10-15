<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    protected static $get = array(
        'a' => 1,
        'b' => 2
    );
    protected static $post = array(
        'a' => 'value',
        'b' => array(5)
    );
    protected static $cookie = array(
        'key1' => 'value1',
        'key2' => 'value2'
    );
    protected static $server = array(
        'REQUEST_METHOD' => 'POST',
        'HTTPS' => 'on',
        'HTTP_HOST' => 'localhost',
        'REQUEST_URI' => '/test.php',
        'HTTP_X_REQUESTED_WITH' => 'XMLHTTPRequest'
    );
    protected static $request;

    public static function setUpBeforeClass()
    {
        self::$request = new \System\Request(self::$get, self::$post, self::$cookie, self::$server);
    }

    public function testGet1()
    {
        $getArray = self::$request->get();
        $this->assertSame($getArray, self::$get);
    }

    public function testGet2()
    {
        $getValue = self::$request->get('b');
        $this->assertSame($getValue, self::$get['b']);
    }

    public function testGet3()
    {
        $getValue = self::$request->get('c');
        $this->assertNull($getValue);
    }

    public function testPost1()
    {
        $postArray = self::$request->post();
        $this->assertSame($postArray, self::$post);
    }

    public function testPost2()
    {
        $postValue = self::$request->post('b');
        $this->assertSame($postValue, self::$post['b']);
    }

    public function testPost3()
    {
        $postValue = self::$request->post('c');
        $this->assertNull($postValue);
    }

    public function testCookie1()
    {
        $cookieArray = self::$request->cookie();
        $this->assertSame($cookieArray, self::$cookie);
    }

    public function testCookie2()
    {
        $cookieValue = self::$request->cookie('key2');
        $this->assertSame($cookieValue, self::$cookie['key2']);
    }

    public function testCookie3()
    {
        $cookieValue = self::$request->cookie('key3');
        $this->assertNull($cookieValue);
    }

    public function testServer1()
    {
        $serverArray = self::$request->server();
        $this->assertSame($serverArray, self::$server);
    }

    public function testServer2()
    {
        $serverValue = self::$request->server('REQUEST_METHOD');
        $this->assertSame($serverValue, self::$server['REQUEST_METHOD']);
    }

    public function testServer3()
    {
        $serverValue = self::$request->server('someKey');
        $this->assertNull($serverValue);
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

    public function testIsCli()
    {
        $client = self::$request->isCli();
        $this->assertFalse($client);
    }

    public function testIsAjax()
    {
        $ajax = self::$request->isAjax();
        $this->assertTrue($ajax);
    }

    public function testIsGet()
    {
        $is = self::$request->isGet();
        $this->assertFalse($is);
    }

    public function testIsPost()
    {
        $is = self::$request->isPost();
        $this->assertTrue($is);
    }

    public function testIsPut()
    {
        $is = self::$request->isPut();
        $this->assertFalse($is);
    }

    public function testIsDelete()
    {
        $is = self::$request->isDelete();
        $this->assertFalse($is);
    }

    public function testIsHead()
    {
        $is = self::$request->isHead();
        $this->assertFalse($is);
    }

    public function testIsOptions()
    {
        $is = self::$request->isOptions();
        $this->assertFalse($is);
    }

    public function testScheme()
    {
        $scheme = self::$request->scheme();
        $this->assertSame('https', $scheme);
    }

    public function testIsMobile()
    {
        $is = self::$request->isMobile();
        $this->assertFalse($is);
    }

    public function testMethod()
    {
        $request = new \System\Request(array('_method' => 'DELETE'), self::$post, self::$cookie, self::$server);
        $isDelete = $request->isDelete();
        $this->assertTrue($isDelete);
    }

}
