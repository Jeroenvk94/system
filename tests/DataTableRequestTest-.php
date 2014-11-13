<?php

namespace System\Tests;

/**
 * 
 *
 * @author Orest
 */
class DataTableRequestTest extends \PHPUnit_Framework_TestCase
{

    protected $table;
    protected $request = array(
        'iDisplayLength' => 10,
        'iDisplayStart' => 20,
        'sEcho' => 5,
        'id' => 10,
        'category' => 5,
        'iSortingCols' => 2,
        'iSortCol_0' => 1,
        'sSortDir_0' => 'asc',
        'iSortCol_1' => 2,
        'sSortDir_1' => 'desc',
    );
    protected $result = array(
        'start' => 20,
        'length' => 10,
        'sortColumns' => array(
            1 => 'asc'
        ),
        'search' => null,
        'echo' => 5,
        'id' => 10,
        'category' => 5
    );
    protected $result2 = array(
        'start' => 20,
        'length' => 10,
        'sortColumns' => array(
            1 => 'asc',
            2 => 'desc'
        ),
        'search' => null,
        'echo' => 5,
        'id' => 10,
        'category' => 5
    );
    protected $requiredParams = array('id', 'category');

    protected function setUp()
    {
        $di = new \System\DI();
        $request = new \System\Request($this->request, [], [], []);
        $di->set('request', $request);
        
        $this->table = new \System\DataTableRequest($di);
    }

    public function testBuildResult1()
    {
        $result = $this->table->buildResult($this->requiredParams);
        $this->assertEquals($this->result, $result);
    }
    
    public function testBuildResult2()
    {
        $result = $this->table->buildResult($this->requiredParams, true);
        $this->assertEquals($this->result2, $result);
    }
    
    public function testBuildResult3()
    {
        $newRequest = $this->request;
        unset($newRequest['category']);
        
        $request = new \System\Request($newRequest, [], [], []);
        $di = new \System\DI();
        $di->set('request', $request);
        
        $this->table = new \System\DataTableRequest($di);
        
        try {
            $this->table->buildResult($this->requiredParams, true);
        } catch (\System\DataTableRequest\ParameterNotFoundException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

}
