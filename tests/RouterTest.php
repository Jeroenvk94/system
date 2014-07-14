<?php

/**
 * 
 *
 * @author Orest
 */
class RouterTest extends \PHPUnit_Framework_TestCase {

    public $router;
    public $baseUrl;

    protected function setUp() {
        $this->router = new \System\Router();
        
        $first = new \System\Router\Route('/first');
        $second = new \System\Router\Route('/second');
        $third = new \System\Router\Regex('/article/:id', null, array('id' => '\d+'));
        
        $this->router->add('first', $first);
        $this->router->add('second', $second);
        $this->router->add('regex', $third);
    }

    public function testFirst() {
        
        $this->assertSame($this->di->get('key'), 'value');
    }

}
