<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Workshop\LegacyWrapper;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/app.php';

Debug::enable(E_ALL & ~E_NOTICE);


$request = Request::createFromGlobals();

$router = $container['router'];
$attributes = $router->matchRequest($request);

$wrapper = new LegacyWrapper(__DIR__ . '/../legacy');
$response = $wrapper->render(
    $attributes['_script']
);

$response->send();