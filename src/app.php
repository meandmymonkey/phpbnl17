<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
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

$container['dispatcher'] = function () use ($container) {
    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(
        new RouterListener(
            $container['router']->getMatcher(),
            new RequestStack()
        )
    );
    
    return $dispatcher;
};

$container['controller_resolver'] = function () use ($container) {
    return new \Workshop\ControllerResolver($container);
};

$container['kernel'] = function () use ($container) {
    $kernel = new HttpKernel(
        $container['dispatcher'],
        $container['controller_resolver']
    );
    
    return $kernel;
};

$container['controller.legacy'] = function () use ($container) {
    return function (Request $request) use ($container) {
        $wrapper = new LegacyWrapper(
            __DIR__ . '/../legacy',
            $container['router']
        );
        $response = $wrapper->render(
            $request->get('_script'),
            $request
        );
        
        return $response;
    };
};