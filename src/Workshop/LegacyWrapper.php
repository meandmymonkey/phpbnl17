<?php

namespace Workshop;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LegacyWrapper
{
    private $basePath;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(
        $basePath,
        UrlGeneratorInterface $router
    ) {
        $this->basePath = $basePath;
        $this->router = $router;
    }

    public function render($legacyScript, Request $request)
    {
        return StreamedResponse::create(
            function () use ($legacyScript, $request) {
                $_SERVER['PHP_SELF'] = $this->basePath.'/'.$legacyScript;
                $_SERVER['SCRIPT_NAME'] = $legacyScript;
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;
                chdir(dirname($this->basePath));
                
                $router = $this->router;

                require $this->basePath.'/'.$legacyScript;
            }
        );
    }

}