<?php

namespace System;

class DataTableRequest
{

    private $result = array(
        'start' => 0,
        'length' => 10,
        'sortColumns' => array(),
        'search' => null,
        'echo' => 0
    );
    private $allowedLength = array(10, 25, 50, 100);
    protected $di;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * Get Request object
     * 
     * @return \System\Request
     * @throws DI\InvalidOffset
     */
    public function getRequest()
    {
        $request = $this->di->get('request');

        if (!($request instanceof Request)) {
            throw new DI\InvalidOffsetException("Request object not defined!");
        }

        return $request;
    }

    public function setDisplayStart($displayStart)
    {
        $this->result['start'] = (int) $displayStart;
    }

    public function getDisplayStart()
    {
        return $this->result['start'];
    }

    public function setDisplayLength($displayLength)
    {
        if (!in_array($displayLength, $this->allowedLength)) {
            $displayLength = $this->allowedLength[0];
        }

        $this->result['length'] = $displayLength;
    }

    public function getDisplayLength()
    {
        return $this->result['length'];
    }

    /**
     * Get the first sort column index
     * 
     * This method always returns the first column
     * index of the current sort column and should
     * be used when you only want to sort against one
     * column. Otherwise, you should use getSortColumns()
     * to get all of the sort column indexes and directions.
     * 
     * @return integer
     */
    public function getSortColumnIndex()
    {
        $keys = array_keys($this->result['sortColumns']);
        return $keys[0];
    }

    /**
     * Get the first sort column direction
     * 
     * This method always returns the first column
     * sort direction of the current sort column and should
     * be used when you only want to sort against one
     * column. Otherwise, you should use getSortColumns()
     * to get all of the sort column indexes and directions.
     * 
     * @return string
     */
    public function getSortDirection()
    {
        $values = array_values($this->result['sortColumns']);
        return $values[0];
    }

    /**
     * Get all of the current sort columns
     * 
     * This method will return an array containing
     * the column index as the key, and the sort
     * direction as the value.
     * 
     * Example:
     *   array(2 => 'asc', 3 => 'desc')
     *
     * @return array
     */
    public function getSortColumns()
    {
        return $this->result['sortColumns'];
    }

    public function setSortColumns($sortColumns)
    {
        $this->result['sortColumns'] = $sortColumns;
    }

    public function setSearch($search)
    {
        $this->result['search'] = $search;
    }

    public function getSearch()
    {
        return $this->result['search'];
    }

    public function hasSearch()
    {
        return !(is_null($this->result['search']) || $this->result['search'] == '');
    }

    public function setEcho($echo)
    {
        $this->result['echo'] = $echo;
    }

    public function getEcho()
    {
        return $this->result['echo'];
    }

    public function setParam($name, $value)
    {
        $this->result[$name] = $value;
    }

    public function buildSortColumnsFromRequest($multisort = false)
    {
        $num = $this->getRequest()->get('iSortingCols', 0);

        $sortCols = array();

        for ($i = 0; $i < $num; $i++) {
            $sortCols[$this->getRequest()->get('iSortCol_' . $i)] = ($this->getRequest()->get('sSortDir_' . $i) == 'asc') ? 'asc' : 'desc';
            if (!$multisort) {
                break;
            }
        }

        $this->setSortColumns($sortCols);
    }

    /**
     * Build result - array of parameters from request
     * 
     * @param array $request
     */
    public function buildResult($requiredParams = array(), $multisort = false)
    {
        $this->setDisplayLength($this->getRequest()->get('iDisplayLength', 0));
        $this->setDisplayStart($this->getRequest()->get('iDisplayStart', 0));
        $this->setEcho($this->getRequest()->get('sEcho', 0));
        $this->setSearch($this->getRequest()->get('sSearch', ''));

        foreach ($requiredParams as $name) {
            $value = $this->getRequest()->get($name);
            if ($value === null) {
                throw new DataTableRequest\ParameterNotFoundException("Required Parameter '{$name}' not found in request!");
            }

            $this->setParam($name, $value);
        }

        $this->buildSortColumnsFromRequest($multisort);

        return $this->result;
    }

    public function getResult()
    {
        return $this->result;
    }

}
