<?php

/**
 * 
 *
 * @author Orest
 */
class DITest extends \PHPUnit_Framework_TestCase {

    public $di;

    protected function setUp() {
        $this->di = new \System\DI();
    }

    public function testSimpleSetGet() {
        $this->di->set('key', 'value');
        $this->assertSame($this->di->get('key'), 'value');
    }
    
    public function testSimpleArraySetGet() {
        $this->di['key'] = 'value';
        $this->assertSame($this->di['key'], 'value');
    }
    
    public function testShared() {
        $this->di->setShared('key2', function(){
            return 'value';
        });
        $this->assertSame($this->di['key2'], 'value');
    }
    
    public function testRewriteShared() {
        $this->di->setShared('key3', function(){
            return 'value';
        });
        $this->di['key3'];
        $this->di->setShared('key3', function(){
            return 'value2';
        });
        
        $this->assertSame($this->di['key3'], 'value2');
    }

}