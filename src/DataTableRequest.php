<?php
namespace System;

class DataTableRequest
{

    private $_result = array(
        'start' => 0,
        'length' => 10,
        'sortColumns' => array(),
        'search' => null,
        'echo' => 0
    );
    private $_allowedLength = array(10, 25, 50, 100);

    public function setDisplayStart($displayStart)
    {
        $this->_result['start'] = (int) $displayStart;
    }

    public function getDisplayStart()
    {
        return $this->_result['start'];
    }

    public function setDisplayLength($displayLength)
    {
        if (!in_array($displayLength, $this->_allowedLength)) {
            $displayLength = $this->_allowedLength[0];
        }

        $this->_result['length'] = $displayLength;
    }

    public function getDisplayLength()
    {
        return $this->_result['length'];
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
        $keys = array_keys($this->_result['sortColumns']);
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
        $values = array_values($this->_result['sortColumns']);
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
        return $this->_result['sortColumns'];
    }

    public function setSortColumns($sortColumns)
    {
        $this->_result['sortColumns'] = $sortColumns;
    }

    public function setSearch($search)
    {
        $this->_result['search'] = $search;
    }

    public function getSearch()
    {
        return $this->_result['search'];
    }

    public function hasSearch()
    {
        return !(is_null($this->_result['search']) || $this->_result['search'] == '');
    }

    public function setEcho($echo)
    {
        $this->_result['echo'] = $echo;
    }

    public function getEcho()
    {
        return $this->_result['echo'];
    }

    public function setParam($name, $value)
    {
        $this->_result[$name] = $value;
    }

    /**
     * Hydrate the current object from a $_GET, $_POST, or $_REQUEST array
     * 
     * @param array $request
     */
    public function getRequest(array $request, $params = array(), $multisort = false)
    {
        $this->setDisplayLength($request['iDisplayLength']);
        $this->setDisplayStart($request['iDisplayStart']);
        $this->setEcho($request['sEcho']);
        $this->setSearch(isset($request['sSearch']) ? $request['sSearch'] : null);

        foreach ($params as $name) {
            if (!isset($request[$name])) {
                throw new DataTableRequestParamNotExists("Required Parameter '{$name}' not found in request!");
            }

            $this->setParam($name, $request[$name]);
        }

        $num = $request['iSortingCols'];

        $sortCols = array();

        for ($x = 0; $x < $num; $x++) {
            $sortCols[$request['iSortCol_' . $x]] = ($request['sSortDir_' . $x] == 'asc') ? 'asc' : 'desc';
            if (!$multisort) {
                break;
            }
        }

        $this->setSortColumns($sortCols);

        return $this->_result;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function buildEmptyResult()
    {
        return array(
            'aaData' => array(),
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0
        );
    }

}

class DataTableRequestParamNotExists extends \Exception
{
    
}

class DataTableRequestInvalidData extends \Exception
{
    
}
