<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class TranslatorTest extends \PHPUnit_Framework_TestCase
{

    protected $translations = array(
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
    protected $allowedLocales = array('en-US', 'en-UK');

    /**
     *
     * @var \System\Translator
     */
    protected $translator;

    /**
     *
     * @var \System\DI
     */
    protected $di;

    public function setUp()
    {
        $this->di = new \System\DI();
        $this->di->set('request', new \System\Request(RequestTest::$get, RequestTest::$post, RequestTest::$cookie, RequestTest::$server));
        $this->translator = new \System\Translator($this->di, $this->allowedLocales);
        $this->translator->setTranslations($this->translations);
    }

    public function testTranslate()
    {
        $this->assertSame('string', $this->translator->_('string'));
        $this->assertSame('one', $this->translator->_(1));
        $this->assertSame('VALUE = test', $this->translator->_('value: %s', array('test')));
        $this->assertSame('context2', $this->translator->_('context', null, 2));
        $this->assertSame('context1', $this->translator->_('context'));
        $this->assertSame('context2 test2', $this->translator->_('context %s', array('test'), 2));
        $this->assertSame('context1 test1', $this->translator->_('context %s', array('test')));
        $this->assertSame('STRING2:1', $this->translator->_('string %1$s,%2$s', array(1, 2)));
    }

    public function testUserLocales()
    {
        $this->assertEmpty($this->translator->getUserLocales());
        $this->assertEmpty($this->translator->getUserLocales()); // read from protected variable

        $this->translator = new \System\Translator($this->di, $this->allowedLocales);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4']));
        $this->assertSame(['ru-RU', 'ru', 'en-US', 'en'], $this->translator->getUserLocales());

        $this->translator = new \System\Translator($this->di, $this->allowedLocales);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => '']));
        $this->assertEmpty($this->translator->getUserLocales());
    }

    public function testBestLocale()
    {
        $this->translator->setBestLocale();
        $this->assertSame('en-US', $this->translator->getLocale());
        
        $this->translator = new \System\Translator($this->di, ['ru', 'uk-UA']);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => 'ru,uk-UA;q=0.8,en;q=0.6']));
        $this->translator->setBestLocale();
        $this->assertSame('ru', $this->translator->getLocale());
        
        $this->translator = new \System\Translator($this->di, ['ru', 'en']);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru-UA;q=0.8,en;q=0.6']));
        $this->translator->setBestLocale();
        $this->assertSame('ru', $this->translator->getLocale());
        
        $this->translator = new \System\Translator($this->di, ['ru-RU', 'en']);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => 'ru,en;q=0.8']));
        $this->translator->setBestLocale();
        $this->assertSame('ru-RU', $this->translator->getLocale());
        
        $this->translator = new \System\Translator($this->di, ['en']);
        $this->di->set('request', new \System\Request(null, null, null, ['HTTP_ACCEPT_LANGUAGE' => 'ru,uk-UA;q=0.8']));
        $this->translator->setBestLocale();
        $this->assertSame('en', $this->translator->getLocale());
        
        $this->translator = new \System\Translator($this->di, []);
        try {
            $this->translator->setBestLocale();
        } catch (\System\Translator\AllowedLocalesEmptyException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testLocale() {
        $this->translator->setLocale('ru');
        $this->assertSame('ru', $this->translator->getLocale());
    }
    
}
