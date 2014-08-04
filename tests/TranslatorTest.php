<?php

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
    public $translator;

    protected function setUp()
    {
        $this->translator = new \System\Translator($this->translations);
    }

    public function testTranslate1()
    {
        $value = $this->translator->_('string');
        $this->assertSame($value, 'string');
    }
    
    public function testTranslate2()
    {
        $value = $this->translator->_(1);
        $this->assertSame($value, 'one');
    }

    public function testTranslate3()
    {
        $value = $this->translator->_('value: %s', array('test'));
        $this->assertSame($value, 'VALUE = test');
    }

    public function testTranslate4()
    {
        $value = $this->translator->_('context', null, 2);
        $this->assertSame($value, 'context2');
    }

    public function testTranslate5()
    {
        $value = $this->translator->_('context');
        $this->assertSame($value, 'context1');
    }

    public function testTranslate6()
    {
        $value = $this->translator->_('context %s', array('test'), 2);
        $this->assertSame($value, 'context2 test2');
    }

    public function testTranslate7()
    {
        $value = $this->translator->_('context %s', array('test'));
        $this->assertSame($value, 'context1 test1');
    }

    public function testTranslate8()
    {
        $value = $this->translator->_('string %1$s,%2$s', array(1, 2));
        $this->assertSame($value, 'STRING2:1');
    }

    public function testUserLocales1()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4';
        $locales = \System\Translator::getUserLocales();
        $this->assertSame($locales, array('ru-RU', 'ru', 'en-US', 'en'));
    }
    
    public function testUserLocales2()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = '';
        $locales = \System\Translator::getUserLocales();
        $this->assertFalse($locales);
    }
    
    public function testBestLocale1()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru,uk-UA;q=0.8,en;q=0.6';
        $best = \System\Translator::getBestLocale(array('ru', 'uk-UA'));

        $this->assertSame($best, 'ru');
    }
    
    public function testBestLocaleFirst2()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = '';
        $best = \System\Translator::getBestLocale(array('ru-UA', 'ru'));

        $this->assertSame($best, 'ru-UA');
    }
    
    public function testBestLocaleFirst3()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru-RU,ru-UA;q=0.8,en;q=0.6';
        $best = \System\Translator::getBestLocale(array('ru', 'en'));

        $this->assertSame($best, 'ru');
    }
    
    public function testBestLocaleFirst4()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru,en;q=0.8';
        $best = \System\Translator::getBestLocale(array('ru-RU', 'en'));

        $this->assertSame($best, 'ru-RU');
    }
}
