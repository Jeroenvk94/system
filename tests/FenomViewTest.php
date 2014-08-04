<?php

/**
 * 
 *
 * @author Orest
 */
class FenomViewTest extends \PHPUnit_Framework_TestCase
{

    public $view;
    public $viewPath;
    public $compilePath;

    protected function setUp()
    {
        $this->viewPath = dirname(__FILE__) . '/../sandbox/templates/fenom/';
        $this->compilePath = dirname(__FILE__) . '/../sandbox/tmp/';
        
        $fenom = Fenom::factory($this->viewPath, $this->compilePath);
        $fenom->setOptions(Fenom::FORCE_COMPILE);
        
        $this->view = new \System\View\Fenom($fenom);
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
        $params = new stdClass();
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
