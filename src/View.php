<?php
namespace System;

/**
 * View adapter
 * 
 * @author Rastor
 */
interface View
{

    public function fetch($template, $vars = array());

    public function display($template, $vars = array());
}