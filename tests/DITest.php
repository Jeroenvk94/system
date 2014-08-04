<?php

/**
 * 
 *
 * @author Orest
 */
class DITest extends \PHPUnit_Framework_TestCase
{

    public $di;

    protected function setUp()
    {
        $this->di = new \System\DI();
    }

    public function testSimpleSetGet()
    {
        $this->di->set('key', 'value');
        $this->assertSame($this->di->get('key'), 'value');
    }

    public function testIsSet1()
    {
        $this->di->set('key', 'value');
        $this->assertTrue(isset($this->di['key']));
    }
    
    public function testIsSet2()
    {
        $this->di->set('key1', 'value1');
        $this->assertFalse(isset($this->di['key2']));
    }
    
    public function testUnset()
    {
        $this->di->set('key2', 'value2');
        unset($this->di['key2']);
        $this->assertFalse(isset($this->di['key2']));
    }

    public function testSimpleArraySetGet()
    {
        $this->di['key'] = 'value';
        $this->assertSame($this->di['key'], 'value');
    }

    public function testShared1()
    {
        $this->di->setShared('key2', function() {
            return 'value2';
        });
        $this->assertSame($this->di['key2'], 'value2');
    }
    
    public function testShared2()
    {
        try {
            $this->di->setShared('key3', function() {
                return 'value3';
            });
        } catch (\Exception $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testRewriteShared()
    {
        $this->di->setShared('key3', function() {
            return 'value';
        });
        $this->di['key3'];
        $this->di->setShared('key3', function() {
            return 'value2';
        });

        $this->assertSame($this->di['key3'], 'value2');
    }

}
