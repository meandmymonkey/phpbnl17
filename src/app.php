<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
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
            'cache_dir' => __DIR__ . '/../cache/router',
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

$container['twig'] = function () use ($container) {
    $loader = new Twig_Loader_Filesystem(
        __DIR__ . '/../templates'
    );

    $twig = new Twig_Environment(
        $loader,
        [
            'cache' => __DIR__ . '/../cache/twig',
            'debug' => true
        ]
    );

    return $twig;
};

$container['db'] = function () use ($container) {
    return new \PDO(
        'mysql:host=localhost;dbname=phpbnl17',
        'root',
        '',
        [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ]
    );
};

$container['controller.todo_list'] = function () use ($container) {

    $twig = $container['twig'];

    return function (Request $request) use ($twig) {
        // ... do something

        return new Response($twig->render('index.html.twig'));
    };
};

$container['controller.todo'] = function () use ($container) {

    $twig = $container['twig'];
    /** @var PDO $db */
    $db = $container['db'];

    return function (Request $request) use ($twig, $db) {
        $query = $db->prepare('SELECT * FROM todo WHERE id = :id');
        $query->bindParam(':id', $request->attributes->get('id'));
        $todo = $query->fetch(\PDO::FETCH_ASSOC);

        return new Response(
            $twig->render(
                'todo.html.twig',
                [
                    'todo' => $todo
                ]
            )
        );
    };
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