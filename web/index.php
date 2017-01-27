<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Workshop\LegacyWrapper;

require_once __DIR__.'/../vendor/autoload.php';  

Debug::enable(E_ALL & ~E_NOTICE);   

$wrapper = new LegacyWrapper();   

$request = Request::createFromGlobals(); 
$response = $wrapper->render(
    $request->getPathInfo(),
    __DIR__.'/../legacy/list.php'
);

$response->send();