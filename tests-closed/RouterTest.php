<?php

/**
 * 
 *
 * @author Orest
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    public $router;
    public $routes = array();
    public $baseUrl = 'http://base.url/';

    protected function setUp()
    {
        $this->router = new \System\Router();
        $this->router->setBaseUrl($this->baseUrl);

        $this->routes['first'] = new \System\Router\Route('/first');
        $this->routes['second'] = new \System\Router\Route('/second');
        $this->routes['third'] = new \System\Router\Regex('/article/:id', null, array('id' => '\d+'));

        $this->router->add('first', $this->routes['first']);
        $this->router->add('second', $this->routes['second']);
        $this->router->add('regex', $this->routes['third']);
    }

    public function testMatching()
    {
        $this->assertTrue($this->routes['first']->isMatch('/first'));
        $this->assertTrue($this->routes['third']->isMatch('/article/65'));
        $this->assertFalse($this->routes['third']->isMatch('/article/a'));
    }

    public function testRouterExec()
    {
        $self = $this;
        $this->router->execute('/article/899', function($route, $name) use ($self) {
            if ($route !== false) {
                $self->assertSame($name, 'regex');
                $self->assertSame($self->router->getRouteName(), $name);
                $self->assertSame($self->router->getFindRoute(), $route);
            } else {
                $this->fail('Route not found');
            }
        });
    }

    public function testRouterRouteNotFound()
    {
        $self = $this;
        $this->router->execute('someUri', function($route, $name) use ($self) {
            $self->assertFalse($route);
            $self->assertNull($name);
        });
    }

    public function testException()
    {
        try {
            $this->router->execute('first', array());
        } catch (\Exception $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testUrlBuilding()
    {
        $this->assertSame($this->router->getRouteUri('first'), '/first');
        $this->assertSame($this->router->getRouteUri('regex', array('id' => 22)), '/article/22');
        $this->assertSame($this->router->getRouteUrl('regex', array('id' => 22)), $this->baseUrl . '/article/22');
    }

}
