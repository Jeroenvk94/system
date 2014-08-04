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
        $this->routes['second'] = new \System\Router\Regex('/article/:id', null, array('id' => '\d+'));
        $this->routes['third'] = new \System\Router\Regex('/setKey/:key/:value');
        $this->routes['noName'] = new \System\Router\Route('/noName');

        $this->router->add('first', $this->routes['first']);
        $this->router->add('regex', $this->routes['second']);
    }

    public function testAdd1()
    {
        try {
            $this->router->add('first', 'text');
        } catch (\Exception $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testAdd2()
    {
        $self = $this;
        $this->router->add('first', $this->routes['first']);
        $this->router->execute('/first', function($route) use ($self) {
            $self->assertEquals($route, $this->routes['first']);
        });
    }

    public function testAdd3()
    {
        $self = $this;
        $this->router->add('second', $this->routes['second']);
        $this->router->execute('/article/5', function($route) use ($self) {
            $self->assertEquals($route, $this->routes['second']);
        });
    }

    public function testAdd4()
    {
        $self = $this;
        $this->router->add('third', $this->routes['third']);
        $this->router->execute('/setKey/a/5', function($route) use ($self) {
            $self->assertEquals($route, $this->routes['third']);
        });
    }

    public function testAdd5()
    {
        $self = $this;
        $this->router->add($this->routes['noName']);
        $this->router->execute('/noName', function($route) use ($self) {
            $self->assertEquals($route, $this->routes['noName']);
        });
    }

    public function testToGetRouteParams()
    {
        $self = $this;
        $this->router->add('third', $this->routes['third']);
        $this->router->execute('/setKey/foo/bar', function($route) use ($self) {
            $self->assertEquals($route->getParams(), array(
                'key' => 'foo',
                'value' => 'bar'
            ));
        });
    }

    public function testGetBaseUrl()
    {
        $this->assertEquals($this->baseUrl, $this->router->getBaseUrl());
    }

    public function testUrlBuilding1()
    {
        $this->assertSame($this->router->getRouteUri('first'), '/first');
    }

    public function testUrlBuilding2()
    {
        $this->assertSame($this->router->getRouteUrl('regex', array('id' => 22)), $this->baseUrl . '/article/22');
    }

    public function testUrlBuilding3()
    {
        $this->assertFalse($this->router->getRouteUrl('name'));
    }

    public function testRouterExecSetup()
    {
        $self = $this;
        $this->router->execute('/article/899', function($route, $name) use ($self) {
            if ($route !== false) {
                $self->assertEquals(array(
                    $self->router->getRouteName(),
                    $self->router->getFindRoute()
                        ), array(
                    $name,
                    $route
                ));
            } else {
                $this->fail('Route not found');
            }
        });
    }

    public function testRouterRouteNotFound()
    {
        $self = $this;
        $this->router->execute('someUri', function($route) use ($self) {
            $self->assertFalse($route);
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

    public function testMatching1()
    {
        $this->assertTrue($this->routes['first']->isMatch('/first'));
    }

    public function testMatching2()
    {
        $this->assertTrue($this->routes['second']->isMatch('/article/65'));
    }

    public function testMatching3()
    {
        $this->assertFalse($this->routes['second']->isMatch('/article/a'));
    }

}
