<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Workshop\LegacyWrapper;

require_once __DIR__.'/../vendor/autoload.php';  

Debug::enable(E_ALL & ~E_NOTICE);   


$request = Request::createFromGlobals(); 


$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_script' => 'list.php']));
$routes->add('home', new Route('/todo', ['_script' => 'todo.php']));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$attributes = $matcher->match($request->getPathInfo());

$wrapper = new LegacyWrapper(__DIR__.'/../legacy');   
$response = $wrapper->render(
    $attributes['_script']
);

$response->send();