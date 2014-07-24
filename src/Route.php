<?php
namespace System;

/**
 *
 * @author Orest
 */
interface Route
{

    public function __construct($pattern, $target);

    public function isMatch($uri);

    public function getUri();
}
