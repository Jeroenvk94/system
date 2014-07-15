<?php

/**
 * 
 *
 * @author Orest
 */
class RouterTest extends \PHPUnit_Framework_TestCase {

    public $router;
    public $routes = array();
    public $baseUrl;

    protected function setUp() {
        $this->router = new \System\Router();

        $this->routes['first'] = new \System\Router\Route('/first');
        $this->routes['second'] = new \System\Router\Route('/second');
        $this->routes['third'] = new \System\Router\Regex('/article/:id', null, array('id' => '\d+'));

        $this->router->add('first', $this->routes['first']);
        $this->router->add('second', $this->routes['second']);
        $this->router->add('regex', $this->routes['third']);
    }

    public function testFirst() {
        $this->assertTrue($this->routes['first']->isMatch('/first'));
    }

    public function testThird() {
        $this->assertTrue($this->routes['third']->isMatch('/article/65'));
        $this->assertFalse($this->routes['third']->isMatch('/article/a'));
    }

    public function testRouterExec() {
        $this->router->execute('article/899', function($route, $name) {
            if ($route !== false) {
                $this->assertSame($name, 'regex');
            }
        });
    }

    public function testRouterRouteNotFound() {
        $this->router->execute('someUri', function($route, $name) use ($this) {
            $this->assertFalse($route);
        });
    }

    public function testException() {
        try {
            $this->router->execute('first', array());
        } catch (\Exception $e) {
            return;
        }
        
        $this->fail('An expected exception has not been raised.');
    }

}
