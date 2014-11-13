<?php

require 'vendor/autoload.php';

$session = new \System\Session();

$session->setName('Session');

$loh = array('1' => array(1, 2), 2);

$session['loh'] = $loh;

var_dump($_SESSION);

$session['loh'][2] = 5;

var_dump($session['loh']['1']);

/*
var_dump(isset($session['loh1']));

var_dump($session->isStarted());*/