<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/app.php';

Debug::enable(E_ALL & ~E_NOTICE);


$request = Request::createFromGlobals();

$router = $container['router'];
$attributes = $router->matchRequest($request);
$request->attributes->add($attributes);

$controller = $container['controller.'.$attributes['_controller']];

$response = $controller($request);

$response->send();