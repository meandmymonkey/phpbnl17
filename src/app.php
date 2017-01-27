<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Workshop\LegacyWrapper;

$container = new Pimple\Container();

$container['router'] = function () use ($container) {
    $locator = new FileLocator(__DIR__ . '/../config');
    $router = new Router(
        new YamlFileLoader($locator),
        'routing.yml',
        [
            'cache_dir' => __DIR__ . '/../cache',
            'debug' => true
        ]
    );

    return $router;
};

$container['controller.legacy'] = function () use ($container) {
    return function (Request $request) {
        $wrapper = new LegacyWrapper(__DIR__ . '/../legacy');
        $response = $wrapper->render(
            $request->get('_script')
        );
        
        return $response;
    };
};