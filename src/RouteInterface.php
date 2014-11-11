<?php
namespace System;

/**
 *
 * @author Orest
 */
interface RouteInterface
{

    public function __construct($pattern, $target);

    public function isMatch($uri);

    public function getUri();
}
