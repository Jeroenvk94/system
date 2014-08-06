<?php
namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class NativeViewTest extends \PHPUnit_Framework_TestCase
{
    public $view;
    public $viewPath;

    protected function setUp()
    {
        $this->viewPath = dirname(__FILE__) . '/../sandbox/templates/native/';
        $this->view = new \System\View\Native();
        $this->view->setViewPath($this->viewPath);
        //$this->tmpPath = dirname(__FILE__) . '../sandbox/tmp/';
    }

    public function testFetch1()
    {
        $result = $this->view->fetch('template.php', array(
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
        
        $result = $this->view->fetch('template.php', $params);
        $this->assertSame($result, file_get_contents($this->viewPath . 'template.result'));
    }
    
    public function testDisplay()
    {
        $this->expectOutputString(file_get_contents($this->viewPath . 'template.result'));
        
        $this->view->display('template.php', array(
            'lnk' => 'http://a.com',
            'text' => 'a.com'
        ));
    }
}
