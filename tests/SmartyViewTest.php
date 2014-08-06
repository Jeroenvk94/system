<?php
namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class SmartyViewTest extends \PHPUnit_Framework_TestCase
{

    public $view;
    public $viewPath;
    public $compilePath;

    public static function setUpBeforeClass()
    {
        //setup default timezone for Smarty class
        date_default_timezone_set('UTC');
    }

    protected function setUp()
    {
        $this->viewPath = dirname(__FILE__) . '/../sandbox/templates/smarty/';
        $this->compilePath = dirname(__FILE__) . '/../sandbox/tmp/';

        $smarty = new \Smarty();
        $smarty->setTemplateDir($this->viewPath)
                ->setCompileDir($this->compilePath);

        $smarty->force_compile = true;

        $this->view = new \System\View\Smarty($smarty);
    }

    public function testFetch1()
    {
        $result = $this->view->fetch('template.tpl', array(
            'lnk' => 'http://a.com',
            'text' => 'a.com'
        ));
        $this->assertSame($result, file_get_contents($this->viewPath . 'template.result'));
    }

    public function testFetch2()
    {
        $params = new \stdClass();
        $params->lnk = 'http://a.com';
        $params->text = 'a.com';

        $result = $this->view->fetch('template.tpl', $params);
        $this->assertSame($result, file_get_contents($this->viewPath . 'template.result'));
    }

    public function testDisplay()
    {
        $this->expectOutputString(file_get_contents($this->viewPath . 'template.result'));

        $this->view->display('template.tpl', array(
            'lnk' => 'http://a.com',
            'text' => 'a.com'
        ));
    }
}
