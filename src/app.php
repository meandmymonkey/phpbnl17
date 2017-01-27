<?php

//require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;

$container = new Pimple\Container();

$container['router'] = function() use ($container)
{
    $locator = new FileLocator(__DIR__.'/../config');
    $router = new Router(
        new YamlFileLoader($locator),
        'routing.yml',
        [
            'cache_dir' => __DIR__.'/../cache',
            'debug' => true
        ]
    );
    
    return $router;
};