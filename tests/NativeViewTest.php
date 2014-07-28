<?php

/**
 * 
 *
 * @author Orest
 */
class NativeViewTest extends \PHPUnit_Framework_TestCase
{

    public $templatesPath;
    public $tmpPath;
    public $view;

    protected function setUp()
    {
        $this->templatesPath = dirname(__FILE__) . '../sandbox/templates/native/';
        $this->view = new \System\View\Native();
        //$this->tmpPath = dirname(__FILE__) . '../sandbox/tmp/';
    }

    public function testSimpleSetGet()
    {
        //$this->assertSame(dirname(__FILE__) . '../sandbox/templates/native/', 2);
    }

}
