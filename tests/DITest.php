<?php

namespace System\Tests;

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

    public function testSet()
    {
        $this->di->set('key', 'value');
        $this->assertSame('value', $this->di->get('key'));
        $this->assertSame('value', $this->di['key']);
        $this->assertTrue($this->di->exist('key'));

        $this->di->delete('key');
        $this->assertNull($this->di->get('key'));

        $this->di['key'] = 'value';
        $this->assertSame('value', $this->di->get('key'));
        $this->assertSame('value', $this->di['key']);
        $this->assertTrue($this->di->exist('key'));

        $this->di->setShared('shared', function() {
            return 25;
        });
        $this->assertSame(25, $this->di->get('shared'));
        $this->assertSame(25, $this->di['shared']);
        $this->assertTrue($this->di->exist('shared'));

        try {
            $this->di->set(null, 'value');
        } catch (\System\DI\InvalidOffsetException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testHelpMethods()
    {
        $this->di->set('session', 1);
        $this->di->set('router', 2);
        $this->di->set('t', 3);
        $this->di->set('request', 4);
        $this->di->set('auth', 5);
        $this->di->set('flashMessages', 6);

        $this->assertSame(1, $this->di->getSession());
        $this->assertSame(2, $this->di->getRouter());
        $this->assertSame(3, $this->di->getTranslator());
        $this->assertSame(4, $this->di->getRequest());
        $this->assertSame(5, $this->di->getAuth());
        $this->assertSame(6, $this->di->getFlashMessages());
    }

}
