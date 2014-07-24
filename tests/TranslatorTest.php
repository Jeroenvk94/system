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

    public function testSimpleString()
    {
        $value = $this->translator->_(1);
        $this->assertSame($value, 'one');
    }

    public function testReplace()
    {
        $value = $this->translator->_('value: %s', array('test'));
        $this->assertSame($value, 'VALUE = test');
    }

    public function testContext()
    {
        $value = $this->translator->_('context', null, 2);
        $this->assertSame($value, 'context2');
    }

    public function testContextWithoutSetup()
    {
        $value = $this->translator->_('context');
        $this->assertSame($value, 'context1');
    }

    public function testContextReplace()
    {
        $value = $this->translator->_('context %s', array('test'), 2);
        $this->assertSame($value, 'context2 test2');
    }

    public function testContextWithoutSetupReplace()
    {
        $value = $this->translator->_('context %s', array('test'));
        $this->assertSame($value, 'context1 test1');
    }

    public function testMultiReplace()
    {
        $value = $this->translator->_('string %1$s,%2$s', array(1, 2));
        $this->assertSame($value, 'STRING2:1');
    }

    public function testUserLocales()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4';
        $locales = \System\Translator::getUserLocales();
        $this->assertSame($locales, array('ru-RU', 'ru', 'en-US', 'en'));
    }

    public function testBestLocale()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru-RU,ru-UA;q=0.8,ru;q=0.6';
        $best = \System\Translator::getBestLocale(array('ru-UA', 'ru'));

        $this->assertSame($best, 'ru-UA');
    }

    public function testBestLocaleFirst()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = '';
        $best = \System\Translator::getBestLocale(array('ru-UA', 'ru'));

        $this->assertSame($best, 'ru-UA');
    }

}
