<?php

namespace Workshop;

use Symfony\Component\HttpFoundation\StreamedResponse;

class LegacyWrapper
{
    private $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function render($legacyScript)
    {
        return StreamedResponse::create(
            function () use ($legacyScript) {
                $_SERVER['PHP_SELF'] = $this->basePath.'/'.$legacyScript;
                $_SERVER['SCRIPT_NAME'] = $legacyScript;
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;
                chdir(dirname($this->basePath));

                require $this->basePath.'/'.$legacyScript;
            }
        );
    }

}