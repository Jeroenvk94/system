<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class TranslatorTest extends \PHPUnit_Framework_TestCase
{

    public $translations = array(
        1 => 'one',
        'value: %s' => 'VALUE = %s',
        'context' => array(
            1 => 'context1',
            2 => 'context2'
        ),
        'context %s' => array(
            1 => 'context1 %s1',
            2 => 'context2 %s2'
        ),
        'string %1$s,%2$s' => 'STRING%2$s:%1s',
    );

    public function testTranslate1()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('string');
        $this->assertSame($value, 'string');
    }

    public function testTranslate2()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_(1);
        $this->assertSame($value, 'one');
    }

    public function testTranslate3()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('value: %s', array('test'));
        $this->assertSame($value, 'VALUE = test');
    }

    public function testTranslate4()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('context', null, 2);
        $this->assertSame($value, 'context2');
    }

    public function testTranslate5()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('context');
        $this->assertSame($value, 'context1');
    }

    public function testTranslate6()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('context %s', array('test'), 2);
        $this->assertSame($value, 'context2 test2');
    }

    public function testTranslate7()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('context %s', array('test'));
        $this->assertSame($value, 'context1 test1');
    }

    public function testTranslate8()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        $value = $translator->_('string %1$s,%2$s', array(1, 2));
        $this->assertSame($value, 'STRING2:1');
    }

    public function testUserLocales1()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations);
        
        $locales = $translator->getUserLocales();
        $this->assertSame($locales, ['ru-RU', 'ru', 'en-US', 'en']);
    }

    public function testUserLocales2()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => '']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations);
        
        $locales = $translator->getUserLocales();
        $this->assertEmpty($locales);
    }
    
    public function testUserLocales3()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], []);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations);
        
        $locales = $translator->getUserLocales();
        $this->assertEmpty($locales);
    }
    
    public function testUserLocales4()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'a']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations);
        
        $locales = $translator->getUserLocales();
        $this->assertSame($locales, array('a'));
    }
    
    public function testUserLocales5()
    {
        $di = new \System\DI();
        $translator = new \System\Translator($di, $this->translations);
        
        try {
            $translator->getUserLocales();
        } catch (\System\Translator\InvalidDIRequestValueException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }
    
    public function testUserLocales6()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], []);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations);
        
        $locales1 = $translator->getUserLocales();
        $locales2 = $translator->getUserLocales();
        
        $this->assertSame($locales1, $locales2);
    }

    public function testBestLocale1()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru,uk-UA;q=0.8,en;q=0.6']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['ru', 'uk-UA']);
        
        $best = $translator->getBestLocale();

        $this->assertSame($best, 'ru');
    }

    public function testBestLocale2()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], []);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['ru-UA', 'ru']);
        
        $best = $translator->getBestLocale();

        $this->assertSame($best, 'ru-UA');
    }

    public function testBestLocale3()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru-UA;q=0.8,en;q=0.6']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['ru', 'en']);
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru-RU,ru-UA;q=0.8,en;q=0.6';
        $best = $translator->getBestLocale();

        $this->assertSame($best, 'ru');
    }

    public function testBestLocale4()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru,en;q=0.8']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['ru-RU', 'en']);
        
        $best = $translator->getBestLocale();

        $this->assertSame($best, 'ru-RU');
    }

    public function testBestLocale5()
    {
        try {
            $di = new \System\DI();
            $translator = new \System\Translator($di, $this->translations, []);
            
            $translator->getBestLocale();
        } catch (\System\Translator\AllowedLocalesEmptyException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }
    
    public function testBestLocale6()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru,en;q=0.8']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['uk-UA', 'en-US']);
        
        $best = $translator->getBestLocale();

        $this->assertSame($best, 'en-US');
    }

    public function testBestLocale7()
    {
        $di = new \System\DI();
        $request = new \System\Request([], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ru,uk-UA;q=0.8,en;q=0.6']);
        $di->set('request', $request);
        $translator = new \System\Translator($di, $this->translations, ['ru', 'uk-UA']);
        
        $best1 = $translator->getBestLocale();
        $best2 = $translator->getBestLocale();

        $this->assertSame($best1, $best2);
    }
    
}
