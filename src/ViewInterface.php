<?php

namespace System;

/**
 * View adapter interface
 * 
 * @author Rastor
 */
interface ViewInterface
{

    public function fetch($template, $vars = array());

    public function display($template, $vars = array());
}
