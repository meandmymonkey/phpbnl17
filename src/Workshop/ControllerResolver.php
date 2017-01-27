<?php

namespace Workshop;

use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getController(Request $request)
    {
        $instance = $this->container[
            'controller.'.$request->attributes->get('_controller')
        ];
        
        return $instance;
    }

    public function getArguments(Request $request, $controller)
    {
        return [$request];
    }
}